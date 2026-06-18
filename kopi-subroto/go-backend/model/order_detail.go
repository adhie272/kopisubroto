package model

import "time"

type OrderDetail struct {
	ID        int64     `json:"id"`
	OrderID   int64     `json:"order_id"`
	MenuID    int64     `json:"menu_id"`
	Jumlah    int       `json:"jumlah"`
	CreatedAt time.Time `json:"created_at"`
	UpdatedAt time.Time `json:"updated_at"`

	// Joined fields (not in table)
	NamaMenu string  `json:"nama_menu,omitempty"`
	Harga    float64 `json:"harga,omitempty"`
}
