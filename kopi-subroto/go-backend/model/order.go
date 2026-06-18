package model

import "time"

type Order struct {
	ID        int64     `json:"id"`
	NomorMeja int       `json:"nomor_meja"`
	Tanggal   time.Time `json:"tanggal"`
	Status    string    `json:"status"`
	CreatedAt time.Time `json:"created_at"`
	UpdatedAt time.Time `json:"updated_at"`
}
