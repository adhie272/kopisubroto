package dto

type TransactionResponse struct {
	ID         int64   `json:"id"`
	OrderID    int64   `json:"order_id"`
	TotalHarga float64 `json:"total_harga"`
	NomorMeja  int     `json:"nomor_meja"`
	Tanggal    string  `json:"tanggal"`
	Status     string  `json:"status"`
}

type SalesSummary struct {
	TotalTransactions int     `json:"total_transactions"`
	TotalRevenue      float64 `json:"total_revenue"`
	TotalOrders       int     `json:"total_orders"`
	AvgOrderValue     float64 `json:"avg_order_value"`
}

type DailySales struct {
	Tanggal    string  `json:"tanggal"`
	Total      float64 `json:"total"`
	JumlahOrder int    `json:"jumlah_order"`
}
