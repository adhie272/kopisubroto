package dto

type MenuRequest struct {
	NamaMenu string  `json:"nama_menu"`
	Harga    float64 `json:"harga"`
	Kategori string  `json:"kategori"`
}

type MenuResponse struct {
	ID       int64   `json:"id"`
	NamaMenu string  `json:"nama_menu"`
	Harga    float64 `json:"harga"`
	Kategori string  `json:"kategori"`
}
