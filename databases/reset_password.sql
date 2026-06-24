-- ============================================================
-- Script Reset Password — db_inventaris
-- Password default = NIM (username) masing-masing pengguna
-- Dibuat: 24 Jun 2026 02:27
-- ============================================================

-- Muhamad Damar Hati (username: 15240045 | password: 15240045)
UPDATE pengguna SET password = '$2y$10$jFNgzcR5rglwRvStMTMQI.jpz9ufdMneX.hYijnIYIrpdeBIjt.bW' WHERE id_pengguna = 1;

-- Ananta Bagas Sasena (username: 15240078 | password: 15240078)
UPDATE pengguna SET password = '$2y$10$ZQ.W5Kc7ehZ.elfLr1tNZ.8pnErWVgiRFRaBayAAcgFLFVTEaoIli' WHERE id_pengguna = 2;

-- Dyas Fathir Arkananta (username: 15240091 | password: 15240091)
UPDATE pengguna SET password = '$2y$10$jGL/HxVu5WDu5slWWlRUUO8q.BiZ5yEXJJP9XcZzyZXFJDPywVFG6' WHERE id_pengguna = 3;

-- Achmad Nazzri Adlan Fatkhuladzi (username: 15240054 | password: 15240054)
UPDATE pengguna SET password = '$2y$10$HxHlt8BJxksjYH0rcFDU1utmfVDz29x9NLmNloLc4NzjrORMJp9zS' WHERE id_pengguna = 4;

-- Akhdan Adiva Sangaji (username: 15240006 | password: 15240006)
UPDATE pengguna SET password = '$2y$10$b/uW261pATE630MaNcPrKOFVsqMOeWNc22NdB7Z8oWjoIbtB.A1Kq' WHERE id_pengguna = 5;

-- Selesai. Semua password direset ke NIM masing-masing.
