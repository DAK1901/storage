# Учет товаров на складе
Проект является сайтом для учета поставок/заказов склада и работает на локальном сервере. Для разработки сайта использованы языки: html, css, javascript, php, MySQL. Локальные сервера: Apache24 и MySQL Sever.
Данный проект создан двумя разработчиками - front-end разработчиком (html, css) и мною (js, php, MySQL).

Склад разделен на секции. В каждой секции может храниться один тип товара (но не является строго за одним типом зафиксированным). Секции ограничены объемом.
Для каждого товара хранится инфомация о его наименовании, весе и объеме.
Поставки: хранится информация о поставках на склад новых товаров, которые необходимо оптимально разместить.
Заказы: хранится информация о заказах, которые необходимо собрать из товаров на складе.
Имеется три основных пользователя склада:
1. Работник склада (в БД является на самом деле ролью, т.к. их может быть сколь угодно много). Функционал: обзор на склад, оптимальное размещение новых товаров для выгрузки товара на склад после поставки, оптимальный сбор заказа.
2. Администартор. Функционал: обзор на склад, обзор на поставки и заказы за задынный период времени, добавление новых типов товара.
3. Менеджер. Функционал: обзор на склад, статистика по поставленным/выгруженным товарам на складе.
