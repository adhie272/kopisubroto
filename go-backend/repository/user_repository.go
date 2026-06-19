package repository

import (
	"database/sql"
	"kopi-subroto-backend/config"
	"kopi-subroto-backend/model"
	"time"
)

type UserRepository struct{}

func NewUserRepository() *UserRepository {
	return &UserRepository{}
}

func (r *UserRepository) FindByEmail(email string) (*model.User, error) {
	query := `SELECT id, name, email, password, role, created_at, updated_at FROM users WHERE email = :1`
	row := config.DB.QueryRow(query, email)

	var user model.User
	var createdAt, updatedAt sql.NullTime
	err := row.Scan(&user.ID, &user.Name, &user.Email, &user.Password, &user.Role, &createdAt, &updatedAt)
	if err != nil {
		return nil, err
	}
	if createdAt.Valid {
		user.CreatedAt = createdAt.Time
	}
	if updatedAt.Valid {
		user.UpdatedAt = updatedAt.Time
	}
	return &user, nil
}

func (r *UserRepository) FindByID(id int64) (*model.User, error) {
	query := `SELECT id, name, email, password, role, created_at, updated_at FROM users WHERE id = :1`
	row := config.DB.QueryRow(query, id)

	var user model.User
	var createdAt, updatedAt sql.NullTime
	err := row.Scan(&user.ID, &user.Name, &user.Email, &user.Password, &user.Role, &createdAt, &updatedAt)
	if err != nil {
		return nil, err
	}
	if createdAt.Valid {
		user.CreatedAt = createdAt.Time
	}
	if updatedAt.Valid {
		user.UpdatedAt = updatedAt.Time
	}
	return &user, nil
}

func (r *UserRepository) Create(user *model.User) error {
	query := `INSERT INTO users (name, email, password, role, created_at, updated_at) 
	          VALUES (:1, :2, :3, :4, :5, :6) RETURNING id INTO :7`
	now := time.Now()
	var id int64
	_, err := config.DB.Exec(query, user.Name, user.Email, user.Password, user.Role, now, now, sql.Out{Dest: &id})
	if err != nil {
		return err
	}
	user.ID = id
	user.CreatedAt = now
	user.UpdatedAt = now
	return nil
}
