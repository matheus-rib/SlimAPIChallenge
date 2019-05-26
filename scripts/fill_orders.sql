INSERT INTO `orders` (`id`, `customer_id`, `total`, `status`, `created_at`, `updated_at`, `cancelDate`) 
              VALUES (1, 1, 189.8, 'CANCELED', NOW(), NOW(), NOW()),
                     (2, 2, 109.9, 'CONCLUDED', NOW(), NOW(), NULL)