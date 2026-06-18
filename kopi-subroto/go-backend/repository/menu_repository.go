package repository

import (
	"database/sql"
	"kopi-subroto-backend/config"
	"kopi-subroto-backend/model"
	"time"
)

type MenuRepository struct{}

func NewMenuRepository() *MenuRepository {
	return &MenuRepository{}
}

func (r *MenuRepository) FindAll() ([]model.Menu, error) {
	query := `SELECT id, nama_menu, harga, kategori, created_at, updated_at FROM menu ORDER BY id DESC`
	rows, err := config.DB.Query(query)
	if err != nil {
		return nil, err
	}
	defer rows.Close()

	var menus []model.Menu
	for rows.Next() {
		var m model.Menu
		var createdAt, updatedAt sql.NullTime
		if err := rows.Scan(&m.ID, &m.NamaMenu, &m.Harga, &m.Kategori, &createdAt, &updatedAt); err != nil {
			return nil, err
		}
		if createdAt.Valid {
			m.CreatedAt = createdAt.Time
		}
		if updatedAt.Valid {
			m.UpdatedAt = updatedAt.Time
		}
		menus = append(menus, m)
	}
	return menus, nil
}

func (r *MenuRepository) FindByKategori(kategori string) ([]model.Menu, error) {
	query := `SELECT id, nama_menu, harga, kategori, created_at, updated_at FROM menu WHERE kategori = :1 ORDER BY id`
	rows, err := config.DB.Query(query, kategori)
	if err != nil {
		return nil, err
	}
	defer rows.Close()

	var menus []model.Menu
	for rows.Next() {
		var m model.Menu
		var createdAt, updatedAt sql.NullTime
		if err := rows.Scan(&m.ID, &m.NamaMenu, &m.Harga, &m.Kategori, &createdAt, &updatedAt); err != nil {
			return nil, err
		}
		if createdAt.Valid {
			m.CreatedAt = createdAt.Time
		}
		if updatedAt.Valid {
			m.UpdatedAt = updatedAt.Time
		}
		menus = append(menus, m)
	}
	return menus, nil
}

func (r *MenuRepository) FindByID(id int64) (*model.Menu, error) {
	query := `SELECT id, nama_menu, harga, kategori, created_at, updated_at FROM menu WHERE id = :1`
	row := config.DB.QueryRow(query, id)

	var m model.Menu
	var createdAt, updatedAt sql.NullTime
	err := row.Scan(&m.ID, &m.NamaMenu, &m.Harga, &m.Kategori, &createdAt, &updatedAt)
	if err != nil {
		return nil, err
	}
	if createdAt.Valid {
		m.CreatedAt = createdAt.Time
	}
	if updatedAt.Valid {
		m.UpdatedAt = updatedAt.Time
	}
	return &m, nil
}

func (r *MenuRepository) Create(menu *model.Menu) error {
	query := `INSERT INTO menu (nama_menu, harga, kategori, created_at, updated_at) 
	          VALUES (:1, :2, :3, :4, :5) RETURNING id INTO :6`
	now := time.Now()
	var id int64
	_, err := config.DB.Exec(query, menu.NamaMenu, menu.Harga, menu.Kategori, now, now, sql.Out{Dest: &id})
	if err != nil {
		return err
	}
	menu.ID = id
	menu.CreatedAt = now
	menu.UpdatedAt = now
	return nil
}

func (r *MenuRepository) Update(menu *model.Menu) error {
	query := `UPDATE menu SET nama_menu = :1, harga = :2, kategori = :3, updated_at = :4 WHERE id = :5`
	now := time.Now()
	_, err := config.DB.Exec(query, menu.NamaMenu, menu.Harga, menu.Kategori, now, menu.ID)
	if err != nil {
		return err
	}
	menu.UpdatedAt = now
	return nil
}

func (r *MenuRepository) Delete(id int64) error {
	query := `DELETE FROM menu WHERE id = :1`
	_, err := config.DB.Exec(query, id)
	return err
}
