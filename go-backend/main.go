package main

import (
	"log"
	"os"

	"kopi-subroto-backend/config"
	"kopi-subroto-backend/handler"
	"kopi-subroto-backend/middleware"
	"kopi-subroto-backend/repository"
	"kopi-subroto-backend/service"

	"github.com/gofiber/fiber/v2"
	"github.com/gofiber/fiber/v2/middleware/cors"
	"github.com/gofiber/fiber/v2/middleware/logger"
	"github.com/joho/godotenv"
	"golang.org/x/crypto/bcrypt"
)

func main() {
	// 1. Load .env
	if err := godotenv.Load(); err != nil {
		log.Println("Warning: .env file not found, using system env vars")
	}

	// 2. Connect to Oracle DB
	config.ConnectDatabase()
	config.InitTables()
	defer config.DB.Close()

	// 3. Seed default admin user
	seedAdminUser()

	// 4. Initialize layers (clean architecture DI)
	userRepo := repository.NewUserRepository()
	menuRepo := repository.NewMenuRepository()
	orderRepo := repository.NewOrderRepository()
	transactionRepo := repository.NewTransactionRepository()

	authService := service.NewAuthService(userRepo)
	menuService := service.NewMenuService(menuRepo)
	orderService := service.NewOrderService(orderRepo, menuRepo, transactionRepo)
	transactionService := service.NewTransactionService(transactionRepo)

	authHandler := handler.NewAuthHandler(authService)
	menuHandler := handler.NewMenuHandler(menuService)
	orderHandler := handler.NewOrderHandler(orderService)
	transactionHandler := handler.NewTransactionHandler(transactionService)

	// 5. Setup Fiber app
	app := fiber.New(fiber.Config{
		AppName: "Subroto Kopi API",
	})

	// Middleware
	app.Use(logger.New())
	app.Use(cors.New(cors.Config{
		AllowOrigins: "http://localhost:5173, http://localhost:3000, http://127.0.0.1:5173",
		AllowHeaders: "Origin, Content-Type, Accept, Authorization",
		AllowMethods: "GET, POST, PUT, DELETE, OPTIONS",
	}))

	// 6. Routes
	api := app.Group("/api")

	// Auth routes (public)
	auth := api.Group("/auth")
	auth.Post("/login", authHandler.Login)

	// Menu routes (public for GET, admin for CUD)
	menu := api.Group("/menu")
	menu.Get("/", menuHandler.GetAll)
	menu.Get("/:id", menuHandler.GetByID)
	menu.Post("/", middleware.AuthRequired(), middleware.AdminOnly(), menuHandler.Create)
	menu.Put("/:id", middleware.AuthRequired(), middleware.AdminOnly(), menuHandler.Update)
	menu.Delete("/:id", middleware.AuthRequired(), middleware.AdminOnly(), menuHandler.Delete)

	// Order routes
	orders := api.Group("/orders")
	orders.Post("/", orderHandler.CreateOrder) // Public — customer order
	orders.Get("/dashboard", middleware.AuthRequired(), orderHandler.GetDashboardStats)
	orders.Get("/", middleware.AuthRequired(), orderHandler.GetAllOrders)
	orders.Get("/:id", middleware.AuthRequired(), orderHandler.GetOrderByID)
	orders.Put("/:id/status", middleware.AuthRequired(), orderHandler.UpdateStatus)

	// Transaction routes (admin only)
	transactions := api.Group("/transactions", middleware.AuthRequired())
	transactions.Get("/", transactionHandler.GetAll)
	transactions.Get("/summary", transactionHandler.GetSummary)
	transactions.Get("/daily", transactionHandler.GetDailySales)

	// 7. Start server
	port := os.Getenv("SERVER_PORT")
	if port == "" {
		port = "8080"
	}

	log.Printf("🚀 Subroto Kopi API running on http://localhost:%s", port)
	if err := app.Listen(":" + port); err != nil {
		log.Fatalf("Failed to start server: %v", err)
	}
}

func seedAdminUser() {
	userRepo := repository.NewUserRepository()

	// Check if admin exists
	_, err := userRepo.FindByEmail("admin@subroto.com")
	if err == nil {
		log.Println("ℹ️  Admin user already exists")
		return
	}

	// Create default admin
	hashedPassword, _ := bcrypt.GenerateFromPassword([]byte("admin123"), bcrypt.DefaultCost)

	_, execErr := config.DB.Exec(
		`INSERT INTO users (name, email, password, role, created_at, updated_at) VALUES (:1, :2, :3, :4, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)`,
		"Admin Subroto", "admin@subroto.com", string(hashedPassword), "admin",
	)
	if execErr != nil {
		log.Printf("Warning seeding admin: %v", execErr)
	} else {
		log.Println("✅ Default admin user created (admin@subroto.com / admin123)")
	}
}
