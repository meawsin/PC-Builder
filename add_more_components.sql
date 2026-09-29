-- ==============================================================
-- PC BUILDER HARDWARE CATALOG EXPANSION (70 New Components)
-- ==============================================================

-- 1. ADD NEW CPUs (10 items)
INSERT INTO CPU (Comp_ID, Generation, Brand, Model, Price, Watt) VALUES
('CPU012', '14th Gen', 'Intel', 'Core i9 14900K', 68000, '125w'),
('CPU013', '14th Gen', 'Intel', 'Core i7 14700K', 52000, '125w'),
('CPU014', '13th Gen', 'Intel', 'Core i5 13600K', 36000, '125w'),
('CPU015', '12th Gen', 'Intel', 'Core i5 12400F', 16500, '65w'),
('CPU016', '12th Gen', 'Intel', 'Core i3 12100', 12000, '60w'),
('CPU017', '7000 Series', 'Ryzen', 'Ryzen 7 7800X3D', 49000, '120w'),
('CPU018', '7000 Series', 'Ryzen', 'Ryzen 5 7600X', 26500, '105w'),
('CPU019', '7000 Series', 'Ryzen', 'Ryzen 9 7900X', 58000, '170w'),
('CPU020', '5000 Series', 'Ryzen', 'Ryzen 7 5700X', 21500, '65w'),
('CPU021', '5000 Series', 'Ryzen', 'Ryzen 5 5600', 14500, '65w')
ON DUPLICATE KEY UPDATE Model=VALUES(Model), Price=VALUES(Price), Watt=VALUES(Watt);

-- 2. ADD NEW MOTHERBOARDS (10 items)
INSERT INTO Motherboard (Comp_ID, Brand, Model, Type, Form_Factor, RAM_slot, Storage_slot, Watt, Price, Supported_CPU_gen) VALUES
('MOBO012', 'MSI', 'MAG B650 TOMAHAWK WIFI', 'DDR5', 'ATX', 4, 3, '60w', 26000, '7000 Series'),
('MOBO013', 'Gigabyte', 'B650M DS3H', 'DDR5', 'Micro ATX', 4, 2, '50w', 18500, '7000 Series'),
('MOBO014', 'ASUS', 'ROG STRIX B760-F GAMING', 'DDR5', 'ATX', 4, 3, '65w', 29500, '14th Gen'),
('MOBO015', 'ASRock', 'B760M Pro RS', 'DDR4', 'Micro ATX', 4, 2, '50w', 15500, '13th Gen'),
('MOBO016', 'MSI', 'PRO B660M-A DDR4', 'DDR4', 'Micro ATX', 4, 2, '50w', 14000, '12th Gen'),
('MOBO017', 'Gigabyte', 'Z690 AORUS ELITE DDR4', 'DDR4', 'ATX', 4, 4, '75w', 24000, '12th Gen'),
('MOBO018', 'ASUS', 'TUF GAMING B550-PLUS', 'DDR4', 'ATX', 4, 2, '60w', 17500, '5000 Series'),
('MOBO019', 'MSI', 'B550M PRO-VDH WIFI', 'DDR4', 'Micro ATX', 4, 2, '50w', 13500, '5000 Series'),
('MOBO020', 'ASRock', 'X570 Phantom Gaming 4', 'DDR4', 'ATX', 4, 2, '70w', 19500, '5900X'),
('MOBO021', 'Gigabyte', 'B450 AORUS ELITE V2', 'DDR4', 'ATX', 4, 2, '55w', 11500, '3600')
ON DUPLICATE KEY UPDATE Model=VALUES(Model), Price=VALUES(Price), Supported_CPU_gen=VALUES(Supported_CPU_gen);

-- 3. ADD NEW RAM (10 items)
INSERT INTO RAM (Comp_ID, Brand, Model, Type, Memory, Watt, Price) VALUES
('RAM011', 'Corsair', 'Vengeance RGB', 'DDR5', '32GB', 15, 16500),
('RAM012', 'G.SKILL', 'Trident Z5 RGB', 'DDR5', '32GB', 15, 18000),
('RAM013', 'Kingston', 'Fury Renegade', 'DDR5', '64GB', 20, 29500),
('RAM014', 'TEAM', 'T-Force Vulcan', 'DDR5', '16GB', 10, 7800),
('RAM015', 'ADATA', 'XPG Lancer RGB', 'DDR5', '32GB', 15, 17200),
('RAM016', 'G.SKILL', 'Ripjaws V', 'DDR4', '16GB', 10, 5200),
('RAM017', 'Kingston', 'Fury Beast RGB', 'DDR4', '16GB', 10, 5800),
('RAM018', 'Corsair', 'Dominator Platinum RGB', 'DDR4', '32GB', 15, 15000),
('RAM019', 'Team', 'T-Force Delta RGB', 'DDR4', '32GB', 15, 11200),
('RAM020', 'Crucial', 'Pro', 'DDR4', '32GB', 15, 9800)
ON DUPLICATE KEY UPDATE Model=VALUES(Model), Price=VALUES(Price);

-- 4. ADD NEW GRAPHICS CARDS (10 items)
INSERT INTO Graphics_Card (Comp_ID, Brand, Type, Model, Memory, Watt, Price) VALUES
('GTX011', 'ASUS', 'DDR6', 'ROG Strix GeForce RTX 4090', '24GB', 450, 245000),
('GTX012', 'Gigabyte', 'DDR6', 'GeForce RTX 4080 Super', '16GB', 320, 145000),
('GTX013', 'MSI', 'DDR6', 'GeForce RTX 4070 Ti Super', '16GB', 285, 115000),
('GTX014', 'Zotac', 'DDR6', 'Gaming GeForce RTX 4070 Super', '12GB', 220, 88000),
('GTX015', 'ASUS', 'DDR6', 'Dual GeForce RTX 4060 Ti', '8GB', 160, 56000),
('GTX016', 'Sapphire', 'DDR6', 'NITRO+ Radeon RX 7900 XTX', '24GB', 355, 138000),
('GTX017', 'PowerColor', 'DDR6', 'Hellhound Radeon RX 7800 XT', '16GB', 263, 74000),
('GTX018', 'XFX', 'DDR6', 'Speedster Radeon RX 7700 XT', '12GB', 245, 62000),
('GTX019', 'Gigabyte', 'DDR6', 'Radeon RX 7600 XT', '16GB', 190, 46000),
('GTX020', 'MSI', 'DDR6', 'GeForce RTX 3050 Ventus 2X', '6GB', 115, 27500)
ON DUPLICATE KEY UPDATE Model=VALUES(Model), Price=VALUES(Price);

-- 5. ADD NEW STORAGE (10 items)
INSERT INTO Storage (Comp_ID, Type, Brand, Model, Storage, Watt, Price) VALUES
('STO011', 'SSD', 'Samsung', '990 PRO NVMe M.2', '2TB', 15, 24500),
('STO012', 'SSD', 'Samsung', '980 PRO NVMe M.2', '1TB', 12, 13500),
('STO013', 'SSD', 'WD', 'Black SN850X NVMe', '2TB', 15, 22500),
('STO014', 'SSD', 'WD', 'Black SN770 NVMe', '1TB', 10, 9800),
('STO015', 'SSD', 'Crucial', 'T500 PCIe Gen4 NVMe', '1TB', 12, 11500),
('STO016', 'SSD', 'Kingston', 'KC3000 PCIe 4.0 NVMe', '2TB', 15, 19500),
('STO017', 'SSD', 'Corsair', 'MP600 PRO LPX', '1TB', 12, 12800),
('STO018', 'HDD', 'Seagate', 'IronWolf Pro NAS', '8TB', 10, 28000),
('STO019', 'HDD', 'WD', 'Red Plus NAS', '4TB', 8, 14500),
('STO020', 'SSD', 'Lexar', 'NM790 M.2 NVMe', '1TB', 10, 9200)
ON DUPLICATE KEY UPDATE Model=VALUES(Model), Price=VALUES(Price);

-- 6. ADD NEW POWER SUPPLIES (10 items)
INSERT INTO Power_Supply (Comp_ID, Brand, Model, Rating, Max_power, Price) VALUES
('PS012', 'Corsair', 'RM850e Fully Modular', '80+ Gold', '850W', 15500),
('PS013', 'Corsair', 'HX1200 Fully Modular', '80+ Platinum', '1200W', 29000),
('PS014', 'Seasonic', 'Vertex GX-1000 ATX 3.0', '80+ Gold', '1000W', 24500),
('PS015', 'Seasonic', 'Focus GX-750 80+ Gold', '80+ Gold', '750W', 13800),
('PS016', 'MSI', 'MAG A750GL PCIE5', '80+ Gold', '750W', 12500),
('PS017', 'Thermaltake', 'Toughpower GF3 1200W ATX 3.0', '80+ Gold', '1200W', 26500),
('PS018', 'Cooler Master', 'V850 Gold i multi', '80+ Gold', '850W', 17200),
('PS019', 'DeepCool', 'PQ850M Gold Modular', '80+ Gold', '850W', 13500),
('PS020', 'ASUS', 'ROG Thor 1200P2 Platinum', '80+ Platinum', '1200W', 42000),
('PS021', 'Antec', 'NeoECO 650W Gold Modular', '80+ Gold', '650W', 8900)
ON DUPLICATE KEY UPDATE Model=VALUES(Model), Price=VALUES(Price);

-- 7. ADD NEW CASINGS (10 items)
INSERT INTO Casing (Comp_ID, Brand, Model, Formfactor, RGB, Extra_Fans, Price) VALUES
('Casing011', 'Lian Li', 'O11 Dynamic EVO', 'ATX', 'Yes', 0, 18500),
('Casing012', 'NZXT', 'H9 Flow Dual-Chamber', 'ATX', 'No', 4, 19800),
('Casing013', 'Corsair', '5000D AIRFLOW Tempered Glass', 'ATX', 'No', 2, 17500),
('Casing014', 'Fractal Design', 'North Chalk White', 'ATX', 'No', 2, 21000),
('Casing015', 'Phanteks', 'NV5 Panoramic Glass', 'ATX', 'Yes', 0, 14500),
('Casing016', 'Montech', 'AIR 903 MAX', 'ATX', 'Yes', 4, 8500),
('Casing017', 'Lian Li', 'Lancool 216 RGB', 'ATX', 'Yes', 3, 12500),
('Casing018', 'ASUS', 'Prime AP201 MicroATX Mesh', 'Micro ATX', 'No', 1, 9200),
('Casing019', 'DeepCool', 'CH370 Micro ATX', 'Micro ATX', 'No', 1, 5500),
('Casing020', 'Thermaltake', 'Tower 200 Mini Chassis', 'Micro ATX', 'No', 2, 13800)
ON DUPLICATE KEY UPDATE Model=VALUES(Model), Price=VALUES(Price);
