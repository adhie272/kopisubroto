package repository

import (
	"database/sql"
	"kopi-subroto-backend/config"
	"kopi-subroto-backend/model"
	"time"
)

type OrderRepository struct{}

func NewOrderRepository() *OrderRepository {
	return &OrderRepository{}
}

func (r *OrderRepository) Create(order *model.Order) error {
	query := `INSERT INTO orders (nomor_meja, tanggal, status, created_at, updated_at) 
	          VALUES (:1, :2, :3, :4, :5) RETURNING id INTO :6`
	now := time.Now()
	var id int64
	_, err := config.DB.Exec(query, order.NomorMeja, now, "pending", now, now, sql.Out{Dest: &id})
	if err != nil {
		return err
	}
	order.ID = id
	order.Tanggal = now
	order.Status = "pending"
	order.CreatedAt = now
	order.UpdatedAt = now
	return nil
}

func (r *OrderRepository) CreateDetail(detail *model.OrderDetail) error {
	query := `INSERT INTO order_details (order_id, menu_id, jumlah, created_at, updated_at)
	          VALUES (:1, :2, :3, :4, :5) RETURNING id INTO :6`
	now := time.Now()
	var id int64
	_, err := config.DB.Exec(query, detail.OrderID, detail.MenuID, detail.Jumlah, now, now, sql.Out{Dest: &id})
	if err != nil {
		return err
	}
	detail.ID = id
	detail.CreatedAt = now
	detail.UpdatedAt = now
	return nil
}

func (r *OrderRepository) FindAll() ([]model.Order, error) {
	query := `SELECT id, nomor_meja, tanggal, status, created_at, updated_at FROM orders ORDER BY created_at DESC`
	rows, err := config.DB.Query(query)
	if err != nil {
		return nil, err
	}
	defer rows.Close()

	var orders []model.Order
	for rows.Next() {
		var o model.Order
		var tanggal, createdAt, updatedAt sql.NullTime
		if err := rows.Scan(&o.ID, &o.NomorMeja, &tanggal, &o.Status, &createdAt, &updatedAt); err != nil {
			return nil, err
		}
		if tanggal.Valid {
			o.Tanggal = tanggal.Time
		}
		if createdAt.Valid {
			o.CreatedAt = createdAt.Time
		}
		if updatedAt.Valid {
			o.UpdatedAt = updatedAt.Time
		}
		orders = append(orders, o)
	}
	return orders, nil
}

func (r *OrderRepository) FindByID(id int64) (*model.Order, error) {
	query := `SELECT id, nomor_meja, tanggal, status, created_at, updated_at FROM orders WHERE id = :1`
	row := config.DB.QueryRow(query, id)

	var o model.Order
	var tanggal, createdAt, updatedAt sql.NullTime
	err := row.Scan(&o.ID, &o.NomorMeja, &tanggal, &o.Status, &createdAt, &updatedAt)
	if err != nil {
		return nil, err
	}
	if tanggal.Valid {
		o.Tanggal = tanggal.Time
	}
	if createdAt.Valid {
		o.CreatedAt = createdAt.Time
	}
	if updatedAt.Valid {
		o.UpdatedAt = updatedAt.Time
	}
	return &o, nil
}

func (r *OrderRepository) FindDetailsByOrderID(orderID int64) ([]model.OrderDetail, error) {
	query := `SELECT od.id, od.order_id, od.menu_id, od.jumlah, od.created_at, od.updated_at,
	                 m.nama_menu, m.harga
	          FROM order_details od
	          JOIN menu m ON od.menu_id = m.id
	          WHERE od.order_id = :1
	          ORDER BY od.id`
	rows, err := config.DB.Query(query, orderID)
	if err != nil {
		return nil, err
	}
	defer rows.Close()

	var details []model.OrderDetail
	for rows.Next() {
		var d model.OrderDetail
		var createdAt, updatedAt sql.NullTime
		if err := rows.Scan(&d.ID, &d.OrderID, &d.MenuID, &d.Jumlah, &createdAt, &updatedAt, &d.NamaMenu, &d.Harga); err != nil {
			return nil, err
		}
		if createdAt.Valid {
			d.CreatedAt = createdAt.Time
		}
		if updatedAt.Valid {
			d.UpdatedAt = updatedAt.Time
		}
		details = append(details, d)
	}
	return details, nil
}

func (r *OrderRepository) UpdateStatus(id int64, status string) error {
	query := `UPDATE orders SET status = :1, updated_at = :2 WHERE id = :3`
	_, err := config.DB.Exec(query, status, time.Now(), id)
	return err
}

func (r *OrderRepository) CountByStatus(status string) (int, error) {
	query := `SELECT COUNT(*) FROM orders WHERE status = :1`
	var count int
	err := config.DB.QueryRow(query, status).Scan(&count)
	return count, err
}

func (r *OrderRepository) CountAll() (int, error) {
	query := `SELECT COUNT(*) FROM orders`
	var count int
	err := config.DB.QueryRow(query).Scan(&count)
	return count, err
}
