package model

import "time"

type Transaction struct {
	ID         int64     `json:"id"`
	OrderID    int64     `json:"order_id"`
	TotalHarga float64   `json:"total_harga"`
	CreatedAt  time.Time `json:"created_at"`
	UpdatedAt  time.Time `json:"updated_at"`
}
