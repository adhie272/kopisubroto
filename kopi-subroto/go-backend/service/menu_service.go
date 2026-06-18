package service

import (
	"errors"
	"kopi-subroto-backend/dto"
	"kopi-subroto-backend/model"
	"kopi-subroto-backend/repository"
)

type MenuService struct {
	menuRepo *repository.MenuRepository
}

func NewMenuService(menuRepo *repository.MenuRepository) *MenuService {
	return &MenuService{menuRepo: menuRepo}
}

func (s *MenuService) GetAll() ([]model.Menu, error) {
	return s.menuRepo.FindAll()
}

func (s *MenuService) GetByKategori(kategori string) ([]model.Menu, error) {
	return s.menuRepo.FindByKategori(kategori)
}

func (s *MenuService) GetByID(id int64) (*model.Menu, error) {
	return s.menuRepo.FindByID(id)
}

func (s *MenuService) Create(req dto.MenuRequest) (*model.Menu, error) {
	if req.NamaMenu == "" || req.Kategori == "" {
		return nil, errors.New("nama menu dan kategori wajib diisi")
	}
	if req.Harga < 1000 {
		return nil, errors.New("harga minimal Rp 1.000")
	}

	menu := &model.Menu{
		NamaMenu: req.NamaMenu,
		Harga:    req.Harga,
		Kategori: req.Kategori,
	}

	if err := s.menuRepo.Create(menu); err != nil {
		return nil, err
	}
	return menu, nil
}

func (s *MenuService) Update(id int64, req dto.MenuRequest) (*model.Menu, error) {
	menu, err := s.menuRepo.FindByID(id)
	if err != nil {
		return nil, errors.New("menu tidak ditemukan")
	}

	if req.NamaMenu != "" {
		menu.NamaMenu = req.NamaMenu
	}
	if req.Harga > 0 {
		menu.Harga = req.Harga
	}
	if req.Kategori != "" {
		menu.Kategori = req.Kategori
	}

	if err := s.menuRepo.Update(menu); err != nil {
		return nil, err
	}
	return menu, nil
}

func (s *MenuService) Delete(id int64) error {
	_, err := s.menuRepo.FindByID(id)
	if err != nil {
		return errors.New("menu tidak ditemukan")
	}
	return s.menuRepo.Delete(id)
}
