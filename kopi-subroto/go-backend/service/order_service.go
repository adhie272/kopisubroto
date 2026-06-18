package service

import (
	"errors"
	"kopi-subroto-backend/dto"
	"kopi-subroto-backend/model"
	"kopi-subroto-backend/repository"
)

type OrderService struct {
	orderRepo       *repository.OrderRepository
	menuRepo        *repository.MenuRepository
	transactionRepo *repository.TransactionRepository
}

func NewOrderService(
	orderRepo *repository.OrderRepository,
	menuRepo *repository.MenuRepository,
	transactionRepo *repository.TransactionRepository,
) *OrderService {
	return &OrderService{
		orderRepo:       orderRepo,
		menuRepo:        menuRepo,
		transactionRepo: transactionRepo,
	}
}

func (s *OrderService) CreateOrder(req dto.CreateOrderRequest) (*dto.OrderResponse, error) {
	if req.NomorMeja <= 0 {
		return nil, errors.New("nomor meja harus valid")
	}
	if len(req.Items) == 0 {
		return nil, errors.New("pesanan harus memiliki minimal 1 item")
	}

	// Create order
	order := &model.Order{
		NomorMeja: req.NomorMeja,
	}
	if err := s.orderRepo.Create(order); err != nil {
		return nil, err
	}

	// Create order details & calculate total
	var totalHarga float64
	var itemResponses []dto.OrderDetailResponse

	for _, item := range req.Items {
		// Validate menu exists
		menu, err := s.menuRepo.FindByID(item.MenuID)
		if err != nil {
			return nil, errors.New("menu tidak ditemukan")
		}

		detail := &model.OrderDetail{
			OrderID: order.ID,
			MenuID:  item.MenuID,
			Jumlah:  item.Jumlah,
		}
		if err := s.orderRepo.CreateDetail(detail); err != nil {
			return nil, err
		}

		subtotal := menu.Harga * float64(item.Jumlah)
		totalHarga += subtotal

		itemResponses = append(itemResponses, dto.OrderDetailResponse{
			ID:       detail.ID,
			MenuID:   menu.ID,
			NamaMenu: menu.NamaMenu,
			Harga:    menu.Harga,
			Jumlah:   item.Jumlah,
			Subtotal: subtotal,
		})
	}

	// Create transaction record
	transaction := &model.Transaction{
		OrderID:    order.ID,
		TotalHarga: totalHarga,
	}
	if err := s.transactionRepo.Create(transaction); err != nil {
		return nil, err
	}

	response := &dto.OrderResponse{
		ID:         order.ID,
		NomorMeja:  order.NomorMeja,
		Tanggal:    order.Tanggal.Format("2006-01-02 15:04:05"),
		Status:     order.Status,
		TotalHarga: totalHarga,
		Items:      itemResponses,
	}

	return response, nil
}

func (s *OrderService) GetAllOrders() ([]dto.OrderResponse, error) {
	orders, err := s.orderRepo.FindAll()
	if err != nil {
		return nil, err
	}

	var responses []dto.OrderResponse
	for _, o := range orders {
		// Get transaction for total_harga
		var totalHarga float64
		tx, err := s.transactionRepo.FindByOrderID(o.ID)
		if err == nil && tx != nil {
			totalHarga = tx.TotalHarga
		}

		responses = append(responses, dto.OrderResponse{
			ID:         o.ID,
			NomorMeja:  o.NomorMeja,
			Tanggal:    o.Tanggal.Format("2006-01-02 15:04:05"),
			Status:     o.Status,
			TotalHarga: totalHarga,
		})
	}
	return responses, nil
}

func (s *OrderService) GetOrderByID(id int64) (*dto.OrderResponse, error) {
	order, err := s.orderRepo.FindByID(id)
	if err != nil {
		return nil, errors.New("pesanan tidak ditemukan")
	}

	details, err := s.orderRepo.FindDetailsByOrderID(id)
	if err != nil {
		return nil, err
	}

	var totalHarga float64
	tx, err := s.transactionRepo.FindByOrderID(id)
	if err == nil && tx != nil {
		totalHarga = tx.TotalHarga
	}

	var items []dto.OrderDetailResponse
	for _, d := range details {
		items = append(items, dto.OrderDetailResponse{
			ID:       d.ID,
			MenuID:   d.MenuID,
			NamaMenu: d.NamaMenu,
			Harga:    d.Harga,
			Jumlah:   d.Jumlah,
			Subtotal: d.Harga * float64(d.Jumlah),
		})
	}

	return &dto.OrderResponse{
		ID:         order.ID,
		NomorMeja:  order.NomorMeja,
		Tanggal:    order.Tanggal.Format("2006-01-02 15:04:05"),
		Status:     order.Status,
		TotalHarga: totalHarga,
		Items:      items,
	}, nil
}

func (s *OrderService) UpdateStatus(id int64, status string) error {
	// Validate status
	validStatuses := map[string]bool{
		"pending":  true,
		"diproses": true,
		"selesai":  true,
	}
	if !validStatuses[status] {
		return errors.New("status tidak valid (pending/diproses/selesai)")
	}

	_, err := s.orderRepo.FindByID(id)
	if err != nil {
		return errors.New("pesanan tidak ditemukan")
	}

	return s.orderRepo.UpdateStatus(id, status)
}

func (s *OrderService) GetDashboardStats() (map[string]interface{}, error) {
	totalOrders, err := s.orderRepo.CountAll()
	if err != nil {
		return nil, err
	}

	pendingCount, _ := s.orderRepo.CountByStatus("pending")
	diprosesCount, _ := s.orderRepo.CountByStatus("diproses")
	selesaiCount, _ := s.orderRepo.CountByStatus("selesai")

	totalRevenue, totalTx, _ := s.transactionRepo.GetSummary()

	return map[string]interface{}{
		"total_orders":       totalOrders,
		"pending_orders":     pendingCount,
		"diproses_orders":    diprosesCount,
		"selesai_orders":     selesaiCount,
		"total_transactions": totalTx,
		"total_revenue":      totalRevenue,
	}, nil
}
