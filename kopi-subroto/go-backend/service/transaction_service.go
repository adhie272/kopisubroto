package service

import (
	"kopi-subroto-backend/dto"
	"kopi-subroto-backend/repository"
)

type TransactionService struct {
	transactionRepo *repository.TransactionRepository
}

func NewTransactionService(transactionRepo *repository.TransactionRepository) *TransactionService {
	return &TransactionService{transactionRepo: transactionRepo}
}

func (s *TransactionService) GetAll() ([]dto.TransactionResponse, error) {
	transactions, err := s.transactionRepo.FindAll()
	if err != nil {
		return nil, err
	}

	var responses []dto.TransactionResponse
	for _, t := range transactions {
		responses = append(responses, dto.TransactionResponse{
			ID:         t.ID,
			OrderID:    t.OrderID,
			TotalHarga: t.TotalHarga,
			Tanggal:    t.CreatedAt.Format("2006-01-02 15:04:05"),
		})
	}
	return responses, nil
}

func (s *TransactionService) GetSummary() (*dto.SalesSummary, error) {
	totalRevenue, totalCount, err := s.transactionRepo.GetSummary()
	if err != nil {
		return nil, err
	}

	avgOrderValue := float64(0)
	if totalCount > 0 {
		avgOrderValue = totalRevenue / float64(totalCount)
	}

	return &dto.SalesSummary{
		TotalTransactions: totalCount,
		TotalRevenue:      totalRevenue,
		TotalOrders:       totalCount,
		AvgOrderValue:     avgOrderValue,
	}, nil
}

func (s *TransactionService) GetDailySales(days int) ([]dto.DailySales, error) {
	results, err := s.transactionRepo.GetDailySales(days)
	if err != nil {
		return nil, err
	}

	var dailySales []dto.DailySales
	for _, r := range results {
		dailySales = append(dailySales, dto.DailySales{
			Tanggal:     r.Tanggal,
			Total:       r.Total,
			JumlahOrder: r.JumlahOrder,
		})
	}
	return dailySales, nil
}
