package repository

import (
	"database/sql"
	"kopi-subroto-backend/config"
	"kopi-subroto-backend/model"
	"time"
)

type TransactionRepository struct{}

func NewTransactionRepository() *TransactionRepository {
	return &TransactionRepository{}
}

func (r *TransactionRepository) Create(tx *model.Transaction) error {
	query := `INSERT INTO transactions (order_id, total_harga, created_at, updated_at)
	          VALUES (:1, :2, :3, :4) RETURNING id INTO :5`
	now := time.Now()
	var id int64
	_, err := config.DB.Exec(query, tx.OrderID, tx.TotalHarga, now, now, sql.Out{Dest: &id})
	if err != nil {
		return err
	}
	tx.ID = id
	tx.CreatedAt = now
	tx.UpdatedAt = now
	return nil
}

func (r *TransactionRepository) FindAll() ([]model.Transaction, error) {
	query := `SELECT t.id, t.order_id, t.total_harga, t.created_at, t.updated_at
	          FROM transactions t
	          ORDER BY t.created_at DESC`
	rows, err := config.DB.Query(query)
	if err != nil {
		return nil, err
	}
	defer rows.Close()

	var transactions []model.Transaction
	for rows.Next() {
		var t model.Transaction
		var createdAt, updatedAt sql.NullTime
		if err := rows.Scan(&t.ID, &t.OrderID, &t.TotalHarga, &createdAt, &updatedAt); err != nil {
			return nil, err
		}
		if createdAt.Valid {
			t.CreatedAt = createdAt.Time
		}
		if updatedAt.Valid {
			t.UpdatedAt = updatedAt.Time
		}
		transactions = append(transactions, t)
	}
	return transactions, nil
}

func (r *TransactionRepository) GetSummary() (float64, int, error) {
	query := `SELECT NVL(SUM(total_harga), 0), COUNT(*) FROM transactions`
	var totalRevenue float64
	var totalCount int
	err := config.DB.QueryRow(query).Scan(&totalRevenue, &totalCount)
	return totalRevenue, totalCount, err
}

func (r *TransactionRepository) GetDailySales(days int) ([]struct {
	Tanggal     string
	Total       float64
	JumlahOrder int
}, error) {
	query := `SELECT TO_CHAR(t.created_at, 'YYYY-MM-DD') as tanggal, 
	                 SUM(t.total_harga) as total,
	                 COUNT(*) as jumlah_order
	          FROM transactions t
	          WHERE t.created_at >= SYSDATE - :1
	          GROUP BY TO_CHAR(t.created_at, 'YYYY-MM-DD')
	          ORDER BY tanggal DESC`
	rows, err := config.DB.Query(query, days)
	if err != nil {
		return nil, err
	}
	defer rows.Close()

	var result []struct {
		Tanggal     string
		Total       float64
		JumlahOrder int
	}
	for rows.Next() {
		var item struct {
			Tanggal     string
			Total       float64
			JumlahOrder int
		}
		if err := rows.Scan(&item.Tanggal, &item.Total, &item.JumlahOrder); err != nil {
			return nil, err
		}
		result = append(result, item)
	}
	return result, nil
}

func (r *TransactionRepository) FindByOrderID(orderID int64) (*model.Transaction, error) {
	query := `SELECT id, order_id, total_harga, created_at, updated_at FROM transactions WHERE order_id = :1`
	row := config.DB.QueryRow(query, orderID)

	var t model.Transaction
	var createdAt, updatedAt sql.NullTime
	err := row.Scan(&t.ID, &t.OrderID, &t.TotalHarga, &createdAt, &updatedAt)
	if err != nil {
		return nil, err
	}
	if createdAt.Valid {
		t.CreatedAt = createdAt.Time
	}
	if updatedAt.Valid {
		t.UpdatedAt = updatedAt.Time
	}
	return &t, nil
}
