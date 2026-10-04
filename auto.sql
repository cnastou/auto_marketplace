USE auto_marketplace;

SET FOREIGN_KEY_CHECKS = 0;

TRUNCATE TABLE bids;
TRUNCATE TABLE transport_requests;
TRUNCATE TABLE vehicles;
TRUNCATE TABLE users;

SET FOREIGN_KEY_CHECKS = 1;

-- =====================================================
-- USERS
-- =====================================================

INSERT INTO users
(name, email, password, created_at, updated_at)
VALUES
('Toronto Auto Transport', 'transport@torontoauto.ca',
 '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC7N9u6wW3Yl5K3vF3i', NOW(), NOW()),

('John Smith', 'john.smith@example.com',
 '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC7N9u6wW3Yl5K3vF3i', NOW(), NOW()),

('Maple Leaf Logistics', 'dispatch@mapleleaflogistics.ca',
 '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC7N9u6wW3Yl5K3vF3i', NOW(), NOW()),

('Sarah Johnson', 'sarah.johnson@example.com',
 '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC7N9u6wW3Yl5K3vF3i', NOW(), NOW()),

('Ontario Vehicle Movers', 'quotes@ontariovehiclemovers.ca',
 '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC7N9u6wW3Yl5K3vF3i', NOW(), NOW());


-- =====================================================
-- VEHICLES
-- =====================================================

INSERT INTO vehicles
(user_id, make, model, year, vin, pickup_location,
 delivery_location, status, created_at, updated_at)
VALUES
(2, 'Toyota', 'Camry', 2024,
 '4T1G11AK5RU123456',
 'Toronto, ON', 'Montreal, QC',
 'available', NOW(), NOW()),

(2, 'Ford', 'F-150', 2023,
 '1FTFW1ED5PFA23456',
 'Toronto, ON', 'Ottawa, ON',
 'available', NOW(), NOW()),

(4, 'Honda', 'Civic', 2025,
 '2HGFE2F50SH345678',
 'Mississauga, ON', 'London, ON',
 'available', NOW(), NOW()),

(4, 'BMW', '3 Series', 2022,
 'WBA5R1C05NFM45678',
 'Toronto, ON', 'Quebec City, QC',
 'available', NOW(), NOW()),

(2, 'Tesla', 'Model 3', 2024,
 '5YJ3E1EA8RF567890',
 'Oakville, ON', 'Montreal, QC',
 'available', NOW(), NOW()),

(4, 'Jeep', 'Wrangler', 2021,
 '1C4HJXDG5MW678901',
 'Burlington, ON', 'Kingston, ON',
 'available', NOW(), NOW());


-- =====================================================
-- TRANSPORT REQUESTS
-- =====================================================

INSERT INTO transport_requests
(user_id, vehicle_id, pickup_date, delivery_date,
 pickup_location, delivery_location, status, notes,
 created_at, updated_at)
VALUES

(2, 1,
 '2026-10-02', '2026-10-04',
 'Toronto, ON', 'Montreal, QC',
 'pending',
 'Standard vehicle transport. Customer prefers pickup after 9 AM.',
 NOW(), NOW()),

(2, 2,
 '2026-10-05', '2026-10-06',
 'Toronto, ON', 'Ottawa, ON',
 'accepted',
 'Vehicle is ready for pickup.',
 NOW(), NOW()),

(4, 3,
 '2026-10-07', '2026-10-08',
 'Mississauga, ON', 'London, ON',
 'in_transit',
 'Please contact customer before delivery.',
 NOW(), NOW()),

(4, 4,
 '2026-10-10', '2026-10-12',
 'Toronto, ON', 'Quebec City, QC',
 'completed',
 'Customer requested careful handling.',
 NOW(), NOW()),

(2, 5,
 '2026-10-15', '2026-10-17',
 'Oakville, ON', 'Montreal, QC',
 'pending',
 'Door-to-door service preferred.',
 NOW(), NOW()),

(4, 6,
 '2026-10-20', '2026-10-21',
 'Burlington, ON', 'Kingston, ON',
 'cancelled',
 'Customer cancelled the shipment.',
 NOW(), NOW());


-- =====================================================
-- BIDS
-- =====================================================

INSERT INTO bids
(transport_request_id, user_id, amount, status, notes,
 created_at, updated_at)
VALUES

(1, 1, 875.00,
 'pending',
 'Standard open transport.',
 NOW(), NOW()),

(1, 3, 825.00,
 'pending',
 'Can provide enclosed transport.',
 NOW(), NOW()),

(1, 5, 910.00,
 'pending',
 'Door-to-door service available.',
 NOW(), NOW()),


(2, 1, 525.00,
 'accepted',
 'Confirmed pickup in Toronto and delivery in Ottawa.',
 NOW(), NOW()),

(2, 3, 560.00,
 'rejected',
 'Standard transport service.',
 NOW(), NOW()),


(3, 5, 475.00,
 'accepted',
 'Driver assigned and shipment is in transit.',
 NOW(), NOW()),

(3, 1, 510.00,
 'rejected',
 'Availability confirmed.',
 NOW(), NOW()),


(4, 3, 1150.00,
 'accepted',
 'Delivery completed successfully.',
 NOW(), NOW()),

(4, 5, 1225.00,
 'rejected',
 'Enclosed transport option.',
 NOW(), NOW()),


(5, 1, 895.00,
 'pending',
 'Open transport with door-to-door pickup.',
 NOW(), NOW()),

(5, 3, 850.00,
 'pending',
 'Experienced Ontario-to-Quebec carrier.',
 NOW(), NOW()),

(5, 5, 940.00,
 'pending',
 'Enclosed transport available.',
 NOW(), NOW());


-- =====================================================
-- VERIFY DATA
-- =====================================================

SELECT 'USERS' AS table_name, COUNT(*) AS records FROM users
UNION ALL
SELECT 'VEHICLES', COUNT(*) FROM vehicles
UNION ALL
SELECT 'TRANSPORT REQUESTS', COUNT(*) FROM transport_requests
UNION ALL
SELECT 'BIDS', COUNT(*) FROM bids;
