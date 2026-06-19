package dto

type OrderItemRequest struct {
	MenuID int64 `json:"menu_id"`
	Jumlah int   `json:"jumlah"`
}

type CreateOrderRequest struct {
	NomorMeja int                `json:"nomor_meja"`
	Items     []OrderItemRequest `json:"items"`
}

type UpdateOrderStatusRequest struct {
	Status string `json:"status"`
}

type OrderDetailResponse struct {
	ID       int64   `json:"id"`
	MenuID   int64   `json:"menu_id"`
	NamaMenu string  `json:"nama_menu"`
	Harga    float64 `json:"harga"`
	Jumlah   int     `json:"jumlah"`
	Subtotal float64 `json:"subtotal"`
}

type OrderResponse struct {
	ID         int64                 `json:"id"`
	NomorMeja  int                   `json:"nomor_meja"`
	Tanggal    string                `json:"tanggal"`
	Status     string                `json:"status"`
	TotalHarga float64               `json:"total_harga"`
	Items      []OrderDetailResponse `json:"items,omitempty"`
}
