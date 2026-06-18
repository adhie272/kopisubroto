package handler

import (
	"kopi-subroto-backend/service"
	"strconv"

	"github.com/gofiber/fiber/v2"
)

type TransactionHandler struct {
	transactionService *service.TransactionService
}

func NewTransactionHandler(transactionService *service.TransactionService) *TransactionHandler {
	return &TransactionHandler{transactionService: transactionService}
}

// GET /api/transactions
func (h *TransactionHandler) GetAll(c *fiber.Ctx) error {
	transactions, err := h.transactionService.GetAll()
	if err != nil {
		return c.Status(fiber.StatusInternalServerError).JSON(fiber.Map{
			"success": false,
			"message": err.Error(),
		})
	}

	return c.JSON(fiber.Map{
		"success": true,
		"data":    transactions,
	})
}

// GET /api/transactions/summary
func (h *TransactionHandler) GetSummary(c *fiber.Ctx) error {
	summary, err := h.transactionService.GetSummary()
	if err != nil {
		return c.Status(fiber.StatusInternalServerError).JSON(fiber.Map{
			"success": false,
			"message": err.Error(),
		})
	}

	return c.JSON(fiber.Map{
		"success": true,
		"data":    summary,
	})
}

// GET /api/transactions/daily?days=7
func (h *TransactionHandler) GetDailySales(c *fiber.Ctx) error {
	daysStr := c.Query("days", "7")
	days, err := strconv.Atoi(daysStr)
	if err != nil || days <= 0 {
		days = 7
	}

	dailySales, err := h.transactionService.GetDailySales(days)
	if err != nil {
		return c.Status(fiber.StatusInternalServerError).JSON(fiber.Map{
			"success": false,
			"message": err.Error(),
		})
	}

	return c.JSON(fiber.Map{
		"success": true,
		"data":    dailySales,
	})
}
