USE YetiCave;

INSERT INTO category SET character_code = 'boards', title = 'Доски и лыжи';
INSERT INTO category SET character_code = 'attachment', title = 'Крепления';
INSERT INTO category SET character_code = 'boots', title = 'Ботинки';
INSERT INTO category SET character_code = 'clothing', title = 'Одежда';
INSERT INTO category SET character_code = 'tools', title = 'Инструменты';
INSERT INTO category SET character_code = 'other', title = 'Разное';

INSERT INTO users SET date_of_registration = '2019-11-01 00:10:00',
                      name = 'Oleksii',
                      password = '123',
                      email = 'oleksii@gmail.com',
                      contacts = 'Kiev, +380933541234';

INSERT INTO users SET date_of_registration = '2019-11-02 00:20:00',
                      name = 'Andrey',
                      password = '321',
                      email = 'andrey@gmail.com',
                      contacts = 'Moscow, +380930991312';

INSERT INTO users SET date_of_registration = '2019-11-01 00:10:00',
                      name = 'Dmitriy',
                      password = '213',
                      email = 'dmitriy@gmail.com',
                      contacts = 'London, +380970973433';

INSERT INTO lots SET date_create = '2019-10-03',
                     title = '2014 Rossignol District Snowboard',
                     description = 'Lorem ipsum',
                     image = 'img/lot-1.jpg',
                     starting_price = '10999',
                     date_of_completion = '2019-11-03',
                     bid_step = '1000',
                     user_id = '1',
                     winner_id = '1',
                     category_id = '1';

INSERT INTO lots SET date_create = '2019-10-11',
                     title = 'DC Ply Mens 2016/2017 Snowboard',
                     description = 'Lorem ipsum',
                     image = 'img/lot-2.jpg',
                     starting_price = '159999',
                     date_of_completion = '2019-11-11',
                     bid_step = '1000',
                     user_id = '2',
                     winner_id = '2',
                     category_id = '1';

INSERT INTO lots SET date_create = '2019-10-13',
                     title = 'Крепления Union Contact Pro 2015 года размер L/XL',
                     description = 'Lorem ipsum',
                     image = 'img/lot-3.jpg',
                     starting_price = '8000',
                     date_of_completion = '2019-11-13',
                     bid_step = '100',
                     user_id = '3',
                     winner_id = '3',
                     category_id = '2';

INSERT INTO lots SET date_create = '2019-10-15',
                     title = 'Ботинки для сноуборда DC Mutiny Charocal',
                     description = 'Lorem ipsum',
                     image = 'img/lot-4.jpg',
                     starting_price = '10999',
                     date_of_completion = '2019-11-15',
                     bid_step = '100',
                     user_id = '1',
                     winner_id = '1',
                     category_id = '3';

INSERT INTO lots SET date_create = '2019-10-17',
                     title = 'Куртка для сноуборда DC Mutiny Charocal',
                     description = 'Lorem ipsum',
                     image = 'img/lot-5.jpg',
                     starting_price = '7500',
                     date_of_completion = '2019-11-17',
                     bid_step = '100',
                     user_id = '2',
                     winner_id = '2',
                     category_id = '4';

INSERT INTO lots SET date_create = '2019-10-25',
                     title = 'Маска Oakley Canopy',
                     description = 'Lorem ipsum',
                     image = 'img/lot-6.jpg',
                     starting_price = '5400',
                     date_of_completion = '2019-11-25',
                     bid_step = '100',
                     user_id = '3',
                     winner_id = '3',
                     category_id = '6';

INSERT INTO rates SET date_starting_rate = '2019-10-25 00:10:00',
                      price = '5400',
                      user_id = '1',
                      lot_id = '6';

INSERT INTO rates SET date_starting_rate = '2019-10-17 00:20:00',
                      price = '7500',
                      user_id = '2',
                      lot_id = '5';

INSERT INTO rates SET date_starting_rate = '2019-10-15 00:30:00',
                      price = '10999',
                      user_id = '3',
                      lot_id = '4';

-- получить все категории
SELECT * FROM category;

-- получить самые новые, открытые лоты. Каждый лот должен включать название, стартовую цену, ссылку на изображение, цену, название категории
SELECT lots.title, lots.starting_price, lots.image, category.title
FROM lots JOIN category ON lots.category_id = category.id
WHERE lots.date_create < CURDATE() ORDER BY lots.date_create DESC;

-- показать лот по его id. Получите также название категории, к которой принадлежит лот
SELECT lots.title, category.title FROM lots JOIN category ON lots.category_id = category.id WHERE lots.id = 2;

-- обновить название лота по его идентификатору
UPDATE lots SET title = 'Маска Canopy Oakley' WHERE id = 6;

-- получить список ставок для лота по его идентификатору с сортировкой по дате
SELECT * FROM rates WHERE lot_id = '6' ORDER BY date_starting_rate ASC;

