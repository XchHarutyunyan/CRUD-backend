# API Документация

Добро пожаловать в документацию по использованию API для продуктов, корзины и категорий. Ниже представлены доступные запросы и методы для каждой из этих сущностей.

**Prefix**
```
/api
```

## Продукты

### Получение продуктов с фильтром
```
GET /products
```
**Этот запрос позволяет получить список продуктов с возможностью применения фильтров.**
```
GET /products?search={имя продукта}&page={страница}&per_page={количество продуктов в странице}
```

### Получение продукта по идентификатору
```
GET /products/{id}
```

### Создание продукта
```
POST /products

{
    name: string,
    price: number,
    description: string,
    available: boolean,
    image?: UploadedFile,
}
```
**Пример**
```
{
    name: "Название продукта",
    price: 29.99,
    description: "Описание продукта",
    available: true,
    image: (загруженный файл)
}
```

### Обновление продукта
```
POST /products/{id}

{
    name: string,
    price: number,
    description: string,
    available: boolean,
    image?: UploadedFile,
}
```

### Удаление продукта
```
DELETE /products/{id}
```

## Корзина

### Добавление продукта в корзину
```
POST /cart

{
    product_id: string,
    quantity: number,
}
```

### Удаление продукта из корзины
```
DELETE /cart/{id}
```

## Категории

### Добавление категории с подкатегориями
```
POST /category

{
    name: string,
    subcategories?: [subcategory],
}

subcategory {
    name: string,
}
```
**Пример**
```
{
    name: "Бытовая техника",
    subcategories: [
        {
            name: "Стиральные машины"
        },
        {
            name: "Утюги и аксессуары"
        },
    ],
}
```

### Удаление категории
```
DELETE /category/{id}
```
