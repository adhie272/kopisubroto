package model

import "time"

type Menu struct {
	ID        int64     `json:"id"`
	NamaMenu  string    `json:"nama_menu"`
	Harga     float64   `json:"harga"`
	Kategori  string    `json:"kategori"`
	CreatedAt time.Time `json:"created_at"`
	UpdatedAt time.Time `json:"updated_at"`
}
