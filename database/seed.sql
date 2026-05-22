-- BuildRent Seed Data
-- Real construction and surveying equipment with realistic pricing

-- ── Categories ───────────────────────────────────────────────────────────────
INSERT INTO categories (name) VALUES
('Concrete & Mixing'),
('Surveying & Levelling'),
('Compaction & Earthworks'),
('Drilling & Breaking'),
('Lifting & Material Handling'),
('Power Generation'),
('Safety & Access');

-- ── Admin User (password: Admin@1234) ────────────────────────────────────────
INSERT INTO users (name, email, password_hash, phone, role) VALUES
('BuildRent Admin', 'admin@buildrent.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+233200000001', 'admin');

-- ── Demo Customer (password: User@1234) ──────────────────────────────────────
INSERT INTO users (name, email, password_hash, phone, address, role) VALUES
('Kofi Mensah',    'kofi@example.com',   '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+233244123456', '14 Independence Ave, Accra', 'user'),
('Ama Asante',     'ama@example.com',    '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+233277654321', '7 Ring Road, Kumasi',        'user'),
('Yaw Darko',      'yaw@example.com',    '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+233201987654', '22 Harbour Road, Tema',      'user');

-- ── Products ─────────────────────────────────────────────────────────────────
INSERT INTO products (category_id, name, description, buy_price, rent_price_day, stock, image_url) VALUES

-- Concrete & Mixing (cat 1)
(1, 'Belle Minimix 150 Concrete Mixer',
 'Robust 150-litre drum capacity electric concrete mixer ideal for small to medium construction projects. Features a tilting drum for easy discharge, heavy-duty steel frame, and 550W motor. Suitable for mixing concrete, mortar, and screed.',
 1850.00, 85.00, 4, 'concrete-mixer-150.jpg'),

(1, 'Altrad Belle 350 Diesel Concrete Mixer',
 'Heavy-duty 350-litre diesel-powered concrete mixer for large-scale site work. Yanmar diesel engine provides reliable performance independent of mains power. High-output mixing ideal for foundations and slabs.',
 4200.00, 160.00, 2, 'concrete-mixer-350.jpg'),

(1, 'Baumax Portable Cement Mixer 120L',
 'Compact 120-litre portable cement mixer with 230V electric motor. Lightweight design with pneumatic tyres for easy site mobility. Perfect for small jobs, patios, and repair work.',
 950.00, 45.00, 6, 'cement-mixer-120.jpg'),

-- Surveying & Levelling (cat 2)
(2, 'Leica Runner 20 Optical Level',
 'Professional automatic optical level with 20x magnification and 1.5mm/km standard deviation. Supplied with tripod and 5-metre staff. Ideal for construction setting out, levelling checks, and quantity surveying.',
 1200.00, 55.00, 5, 'leica-runner-20.jpg'),

(2, 'Bosch GLL 3-80 Professional Laser Level',
 'Self-levelling cross-line laser with 360-degree horizontal and two vertical lines. 80-metre working range with receiver, ±0.2mm/m accuracy. Includes mount, receiver, and carry case. Suitable for interior fit-out and setting out.',
 890.00, 40.00, 8, 'bosch-laser-level.jpg'),

(2, 'Leica TS06 Total Station 5"',
 'High-precision total station with 5-second angular accuracy and 3mm+2ppm EDM. Features onboard software for setting out, traversing, and area calculation. Supplied with tribrach, tripod, and two prisms.',
 18500.00, 350.00, 2, 'leica-total-station.jpg'),

(2, 'Stanley FatMax Automatic Level Kit',
 'Entry-level automatic level with 32x magnification, ideal for general levelling tasks on smaller construction sites. Kit includes tripod and 4m staff.',
 420.00, 22.00, 10, 'stanley-level-kit.jpg'),

-- Compaction & Earthworks (cat 3)
(3, 'Wacker Neuson BS60-2 Jumping Jack Compactor',
 'Petrol-powered rammer compactor with 60kg operating weight. 280mm shoe width ideal for compacting granular and cohesive soils in trenches and confined areas. Honda GX160 engine, 680 blows/min.',
 2800.00, 95.00, 3, 'wacker-rammer.jpg'),

(3, 'Bomag BP18/45 Reversible Plate Compactor',
 'Reversible vibratory plate compactor with 18kN centrifugal force and 450mm plate width. Suitable for large-area compaction of granular soils, asphalt, and gravel. Honda engine, water sprinkler kit included.',
 3600.00, 130.00, 3, 'bomag-plate.jpg'),

(3, 'Belle RTX 55 Reversible Plate Compactor',
 'Mid-range reversible plate compactor with 55kN compaction force. Ideal for road repair, driveways, and paving projects. Anti-vibration handlebar for operator comfort.',
 2200.00, 80.00, 4, 'belle-plate.jpg'),

-- Drilling & Breaking (cat 4)
(4, 'Hilti TE 70 ATC Rotary Hammer Drill',
 'Professional SDS-Max rotary hammer with 8.8J impact energy and Active Torque Control. 1010W motor capable of drilling up to 52mm diameter in concrete. Includes carrying case and 6-piece SDS-Max drill set.',
 2100.00, 75.00, 6, 'hilti-te70.jpg'),

(4, 'Bosch GSH 16-28 Professional Demolition Hammer',
 'Heavy-duty demolition hammer with 41J impact energy. SDS-Max tooling accepts chisels and spades for breaking concrete, masonry, and asphalt. Anti-vibration handle, 1750W motor.',
 3200.00, 110.00, 3, 'bosch-demolition.jpg'),

(4, 'Atlas Copco LP 9-20 Pneumatic Rock Drill',
 'Pneumatic drifter drill for rock drilling and bolt-hole drilling. Requires compressor (available separately). 20mm hex shank, 63mm maximum bit diameter, suitable for quarrying and rock anchor installation.',
 4800.00, 180.00, 2, 'atlas-rock-drill.jpg'),

-- Lifting & Material Handling (cat 5)
(5, 'Genie GS-1930 Electric Scissor Lift',
 'Electric scissor lift with 7.79m platform height and 227kg capacity. Zero-emission indoor use, non-marking tyres. Ideal for interior finishing, MEP installation, and ceiling work.',
 28000.00, 420.00, 1, 'genie-scissor.jpg'),

(5, 'Altrad Belle 250kg Material Hoist',
 'Electric material hoist with 250kg capacity and 10-metre mast height. Safe for lifting bricks, blocks, mortar, and building materials to upper floors. Simple single-phase operation.',
 3500.00, 120.00, 2, 'material-hoist.jpg'),

(5, 'Youngman Masterstep Scaffold Tower 4m',
 'Aluminium modular scaffold tower reaching 4m working height. Quick-assembly design, 950mm x 1450mm platform with trapdoor. Suitable for interior and exterior use up to 2 persons.',
 1800.00, 55.00, 5, 'scaffold-tower.jpg'),

-- Power Generation (cat 6)
(6, 'Honda EU22i Inverter Generator 2.2kVA',
 'Ultra-quiet 2.2kVA inverter generator with Honda GX100 engine. Produces clean stable power suitable for sensitive electronics and tools. 4-hour runtime at rated load, weighs only 21kg.',
 2400.00, 90.00, 4, 'honda-generator.jpg'),

(6, 'Pramac S8000 8kVA Diesel Generator',
 'Heavy-duty 8kVA open-frame diesel generator. Suitable for powering multiple tools and site offices simultaneously. 15-litre fuel tank, AVR voltage regulation, electric start.',
 5500.00, 200.00, 2, 'pramac-generator.jpg'),

-- Safety & Access (cat 7)
(7, 'Werner 3T Industrial Extension Ladder 6.2m',
 'Industrial-grade aluminium extension ladder reaching 6.2m. EN131 Professional rated, 150kg duty rating. Anti-slip rubber feet and V-rung design for improved grip.',
 480.00, 18.00, 10, 'werner-ladder.jpg'),

(7, 'JSP EVO VISTAlens Hard Hat Yellow',
 'Type 1 safety helmet with integrated clear visor, ventilated ABS shell, and 6-point webbing harness. Meets EN397 standard. Suitable for construction and civil engineering sites.',
 35.00, NULL, 50, 'jsp-hard-hat.jpg');

-- ── Sample Orders ─────────────────────────────────────────────────────────────
INSERT INTO orders (user_id, reference, total_amount, status, delivery_name, delivery_address, notes) VALUES
(2, 'BR-2024-00001', 255.00, 'confirmed',  'Kofi Mensah', '14 Independence Ave, Accra, Ghana', 'Please deliver before 8am'),
(3, 'BR-2024-00002', 700.00, 'fulfilled',  'Ama Asante',  '7 Ring Road, Kumasi, Ghana',        NULL),
(4, 'BR-2024-00003', 850.00, 'pending',    'Yaw Darko',   '22 Harbour Road, Tema, Ghana',      'Site access via rear gate');

-- ── Sample Order Items ────────────────────────────────────────────────────────
INSERT INTO order_items (order_id, product_id, type, quantity, unit_price, rental_start, rental_end, rental_days, line_total) VALUES
-- Order 1: Kofi rents concrete mixer for 3 days
(1, 1, 'rent', 1, 85.00, '2024-06-10', '2024-06-12', 3, 255.00),
-- Order 2: Ama rents total station for 2 days
(2, 6, 'rent', 1, 350.00, '2024-06-15', '2024-06-16', 2, 700.00),
-- Order 3: Yaw rents plate compactor + jumping jack for 5 days each
(3, 9, 'rent', 1, 130.00, '2024-06-20', '2024-06-24', 5, 650.00),
(3, 8, 'rent', 1, 95.00,  '2024-06-20', '2024-06-22', 2, 190.00);
