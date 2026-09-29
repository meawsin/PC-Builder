-- Seed 7 additional realistic users
INSERT INTO user (U_ID, Name, Email, Phone_Number, Address, Password) VALUES
('U004', 'Sarah Jenkins', 'sarah.j@example.com', '01711223344', 'Road 11, Dhanmondi, Dhaka', 'sarah123'),
('U005', 'Alex Mercer', 'alex.mercer@example.com', '01822334455', 'Block C, Banani, Dhaka', 'alex2024'),
('U006', 'David Miller', 'david.m@example.com', '01933445566', 'Gulshan Avenue, Dhaka', 'davidpass'),
('U007', 'Emily Chen', 'emily.chen@example.com', '01644556677', 'Sector 7, Uttara, Dhaka', 'emilypwd'),
('U008', 'Tanvir Ahmed', 'tanvir.ahmed@example.com', '01555667788', 'Section 10, Mirpur, Dhaka', 'tanvir789'),
('U009', 'Fahim Rahman', 'fahim.r@example.com', '01766778899', 'Block D, Bashundhara R/A, Dhaka', 'fahim123'),
('U010', 'Nafis Iqbal', 'nafis.iqbal@example.com', '01877889900', 'Ring Road, Mohammadpur, Dhaka', 'nafis456')
ON DUPLICATE KEY UPDATE Name=VALUES(Name);

-- Seed realistic custom builds
INSERT INTO builds (Build_ID, User_ID, CPU_ID, Motherboard_ID, RAM_ID, Storage_ID, Graphics_Card_ID, PSU_ID, Casing_ID, Build_Number, Estimated_Price, Build_Date) VALUES
(2, 'U002', 'CPU011', 'Mobo001', 'RAM010', 'STO002', 'GTX004', 'PS004', 'Casing006', '2', 133900, NOW() - INTERVAL 5 DAY),
(3, 'U004', 'CPU004', 'Mobo004', 'RAM005', 'STO004', 'GTX007', 'PS001', 'Casing001', '1', 69089, NOW() - INTERVAL 4 DAY),
(4, 'U005', 'CPU008', 'Mobo003', 'RAM010', 'STO006', 'GTX010', 'PS010', 'Casing007', '1', 227800, NOW() - INTERVAL 3 DAY),
(5, 'U006', 'CPU010', 'Mobo003', 'RAM005', 'STO007', 'GTX005', 'PS008', 'Casing009', '1', 94900, NOW() - INTERVAL 2 DAY),
(6, 'U007', 'CPU001', 'Mobo001', 'RAM001', 'STO002', 'GTX004', 'PS004', 'Casing006', '1', 130600, NOW() - INTERVAL 1 DAY),
(7, 'U008', 'CPU005', 'Mobo005', 'RAM007', 'STO006', 'GTX006', 'PS007', 'Casing010', '1', 127000, NOW())
ON DUPLICATE KEY UPDATE Estimated_Price=VALUES(Estimated_Price);

-- Seed orders corresponding to these builds
INSERT INTO orders (Order_ID, U_ID, Build_ID, Price, Status) VALUES
(2, 'U002', 2, 133900, 'processing'),
(3, 'U004', 3, 69089, 'delivered'),
(4, 'U005', 4, 227800, 'completed'),
(5, 'U006', 5, 94900, 'pending'),
(6, 'U007', 6, 130600, 'delivered'),
(7, 'U008', 7, 127000, 'shipped')
ON DUPLICATE KEY UPDATE Status=VALUES(Status), Price=VALUES(Price);

-- Seed chat messages with expert responses
INSERT INTO messages (Message_ID, User_ID, Admin_ID, Message, Response, Created_At) VALUES
(1, 'U002', 'A001', 'Will an RTX 4060 run well with a 650W PSU and Intel Core i5?', 'Yes, an RTX 4060 has a TDP of around 115W, so a quality 650W PSU is more than sufficient and provides plenty of headroom.', NOW() - INTERVAL 3 DAY),
(2, 'U004', 'A002', 'Can I install DDR5 RAM on an AMD B450 motherboard?', 'No, AMD B450 motherboards only support DDR4 memory. You will need an AM5 motherboard like B650 or compatible DDR5 boards for DDR5.', NOW() - INTERVAL 2 DAY),
(3, 'U007', 'A001', 'How many extra fans does the NZXT H510 Elite case support?', 'The NZXT H510 Elite comes with 2 Aer RGB fans in the front and supports an additional top and rear exhaust fan.', NOW() - INTERVAL 1 DAY),
(4, 'U005', NULL, 'Do you offer custom liquid cooling installation for RTX 3090 builds?', NULL, NOW())
ON DUPLICATE KEY UPDATE Message=VALUES(Message);
