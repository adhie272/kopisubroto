package handler

import (
	"kopi-subroto-backend/dto"
	"kopi-subroto-backend/service"
	"strconv"

	"github.com/gofiber/fiber/v2"
)

type OrderHandler struct {
	orderService *service.OrderService
}

func NewOrderHandler(orderService *service.OrderService) *OrderHandler {
	return &OrderHandler{orderService: orderService}
}

// POST /api/orders — Customer creates order (no auth required)
func (h *OrderHandler) CreateOrder(c *fiber.Ctx) error {
	var req dto.CreateOrderRequest
	if err := c.BodyParser(&req); err != nil {
		return c.Status(fiber.StatusBadRequest).JSON(fiber.Map{
			"success": false,
			"message": "Request tidak valid",
		})
	}

	response, err := h.orderService.CreateOrder(req)
	if err != nil {
		return c.Status(fiber.StatusBadRequest).JSON(fiber.Map{
			"success": false,
			"message": err.Error(),
		})
	}

	return c.Status(fiber.StatusCreated).JSON(fiber.Map{
		"success": true,
		"message": "Pesanan berhasil dibuat! Terima kasih.",
		"data":    response,
	})
}

// GET /api/orders — Admin/kasir list all orders
func (h *OrderHandler) GetAllOrders(c *fiber.Ctx) error {
	orders, err := h.orderService.GetAllOrders()
	if err != nil {
		return c.Status(fiber.StatusInternalServerError).JSON(fiber.Map{
			"success": false,
			"message": err.Error(),
		})
	}

	return c.JSON(fiber.Map{
		"success": true,
		"data":    orders,
	})
}

// GET /api/orders/:id — Get order detail
func (h *OrderHandler) GetOrderByID(c *fiber.Ctx) error {
	id, err := strconv.ParseInt(c.Params("id"), 10, 64)
	if err != nil {
		return c.Status(fiber.StatusBadRequest).JSON(fiber.Map{
			"success": false,
			"message": "ID tidak valid",
		})
	}

	order, err := h.orderService.GetOrderByID(id)
	if err != nil {
		return c.Status(fiber.StatusNotFound).JSON(fiber.Map{
			"success": false,
			"message": err.Error(),
		})
	}

	return c.JSON(fiber.Map{
		"success": true,
		"data":    order,
	})
}

// PUT /api/orders/:id/status — Update order status
func (h *OrderHandler) UpdateStatus(c *fiber.Ctx) error {
	id, err := strconv.ParseInt(c.Params("id"), 10, 64)
	if err != nil {
		return c.Status(fiber.StatusBadRequest).JSON(fiber.Map{
			"success": false,
			"message": "ID tidak valid",
		})
	}

	var req dto.UpdateOrderStatusRequest
	if err := c.BodyParser(&req); err != nil {
		return c.Status(fiber.StatusBadRequest).JSON(fiber.Map{
			"success": false,
			"message": "Request tidak valid",
		})
	}

	if err := h.orderService.UpdateStatus(id, req.Status); err != nil {
		return c.Status(fiber.StatusBadRequest).JSON(fiber.Map{
			"success": false,
			"message": err.Error(),
		})
	}

	return c.JSON(fiber.Map{
		"success": true,
		"message": "Status pesanan berhasil diupdate",
	})
}

// GET /api/orders/dashboard — Dashboard stats
func (h *OrderHandler) GetDashboardStats(c *fiber.Ctx) error {
	stats, err := h.orderService.GetDashboardStats()
	if err != nil {
		return c.Status(fiber.StatusInternalServerError).JSON(fiber.Map{
			"success": false,
			"message": err.Error(),
		})
	}

	return c.JSON(fiber.Map{
		"success": true,
		"data":    stats,
	})
}
