package config

import (
	"database/sql"
	"fmt"
	"log"
	"os"

	_ "github.com/sijms/go-ora/v2"
)

var DB *sql.DB

func ConnectDatabase() {
	host := os.Getenv("DB_HOST")
	port := os.Getenv("DB_PORT")
	service := os.Getenv("DB_SERVICE")
	user := os.Getenv("DB_USER")
	password := os.Getenv("DB_PASSWORD")

	// go-ora DSN format: oracle://user:password@host:port/service_name
	dsn := fmt.Sprintf("oracle://%s:%s@%s:%s/%s", user, password, host, port, service)

	var err error
	DB, err = sql.Open("oracle", dsn)
	if err != nil {
		log.Fatalf("Failed to connect to Oracle DB: %v", err)
	}

	DB.SetMaxOpenConns(10)
	DB.SetMaxIdleConns(5)

	if err = DB.Ping(); err != nil {
		log.Fatalf("Failed to ping Oracle DB: %v", err)
	}

	log.Println("✅ Oracle Database connected successfully")
}

func InitTables() {
	tables := []string{
		`BEGIN
			EXECUTE IMMEDIATE 'CREATE TABLE users (
				id          NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
				name        VARCHAR2(255) NOT NULL,
				email       VARCHAR2(255) NOT NULL UNIQUE,
				password    VARCHAR2(255) NOT NULL,
				role        VARCHAR2(20) DEFAULT ''kasir'' CHECK (role IN (''admin'', ''kasir'')),
				created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
				updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
			)';
		EXCEPTION WHEN OTHERS THEN
			IF SQLCODE = -955 THEN NULL; ELSE RAISE; END IF;
		END;`,
		`BEGIN
			EXECUTE IMMEDIATE 'CREATE TABLE menu (
				id          NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
				nama_menu   VARCHAR2(255) NOT NULL,
				harga       NUMBER(12,2) NOT NULL,
				kategori    VARCHAR2(100) NOT NULL,
				created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
				updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
			)';
		EXCEPTION WHEN OTHERS THEN
			IF SQLCODE = -955 THEN NULL; ELSE RAISE; END IF;
		END;`,
		`BEGIN
			EXECUTE IMMEDIATE 'CREATE TABLE orders (
				id          NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
				nomor_meja  NUMBER NOT NULL,
				tanggal     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
				status      VARCHAR2(20) DEFAULT ''pending'' CHECK (status IN (''pending'', ''diproses'', ''selesai'')),
				created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
				updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
			)';
		EXCEPTION WHEN OTHERS THEN
			IF SQLCODE = -955 THEN NULL; ELSE RAISE; END IF;
		END;`,
		`BEGIN
			EXECUTE IMMEDIATE 'CREATE TABLE order_details (
				id          NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
				order_id    NUMBER NOT NULL REFERENCES orders(id),
				menu_id     NUMBER NOT NULL REFERENCES menu(id),
				jumlah      NUMBER NOT NULL,
				created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
				updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
			)';
		EXCEPTION WHEN OTHERS THEN
			IF SQLCODE = -955 THEN NULL; ELSE RAISE; END IF;
		END;`,
		`BEGIN
			EXECUTE IMMEDIATE 'CREATE TABLE transactions (
				id          NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
				order_id    NUMBER NOT NULL REFERENCES orders(id),
				total_harga NUMBER(12,2) NOT NULL,
				created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
				updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
			)';
		EXCEPTION WHEN OTHERS THEN
			IF SQLCODE = -955 THEN NULL; ELSE RAISE; END IF;
		END;`,
	}

	for _, ddl := range tables {
		if _, err := DB.Exec(ddl); err != nil {
			log.Printf("Warning creating table: %v", err)
		}
	}

	log.Println("✅ Database tables initialized")
}
