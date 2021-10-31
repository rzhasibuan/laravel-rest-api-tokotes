# Membangun rest-api dengan mengunakan laravel 

## installasi dan confirguration project laravel

### install project
```phpt
composer create-project --prefer-dist laravel/laravel api.toko   
```
### confirgurasi project 

```phpt
pada file .env tambahkan database kita 

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=databaseprojectkita
DB_USERNAME=root
DB_PASSWORD=password
```

### migrate project 
```phpt
php artisan migrate
```
## Add table ke dalam database 
### buat model dan migration beserta factory 
```phpt
 php artisan make:model Category -mf
 php artisan make:model Product -mf
```

### migration file
buka file migration yang telah kita buat tadi dan tambahkan kode seperti dibawah ini

```phpt
migration file category.php

Schema::create('categories', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('slug')->unique();
    $table->timestamps();
});

// berfungsi untuk membuat table categories 
```

```phpt
migration file product.php

Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->foreignId('category_id')->constrained();
    $table->string('name');
    $table->string('slug')->unique();
    $table->double('price');
    $table->text('description');
    $table->timestamps();
});

// berfungsi untuk membuat table product 
```
### buat relasi antar table category dan product 
hubungan relasi antara category dan product adalah one to many 
karna satu buah category dapat memiliki banyak product tetapi satu product hanya dapat memilkiki 1 category saja
#### Buka Model
pada model category tambahkan method berikut  
```phpt
model Category.php

public function products()
{
    return $this->hasMany(Product::class);
}
```
kemudian tambahkan method berikut pada Product
```phpt
model Product.php

public function category()
{
    return $this->belongsTo(Category::class);
}
```
### migrate fresh 
```phpt
php artisan migrate:fresh
//berfungsi untuk merefresh migration yang sebelumnya pernah kita lakukan 
```

### Kemudian tambahkan dummy data dengan factory
buka file pada directory factory dan tambahkan dummy data seperti dibawah ini
```phpt
CategoryFactory.php

// tambahkan kode dibawah ini pada method definition 
return [
    'name' => $name = $this->faker->sentence,
    'slug' => Str::slug($name),
];

// kode tersebut berfungsi untuk menambahkan data dummy kedalam table category yang telah kita buat sebelumnya 
```

```phpt
ProductFactory.php

// tambahkan kode dibawah ini pada method definition 
return [
    'category_id' => Category::factory(),
    'name' => $name = $this->faker->sentence,
    'slug' => Str::slug($name),
    'description'=> $this->faker->paragraph(25),
    'price' => rand(111111,999999),
];
// kode berikut ini juga berfungsi untuk menambahkan data dummy 
```

### tambahkan factory data tadi kedalam seeders
buka DatabaseSeeder kemudian tambahkan kode seperti dibawah ini
```phpt
// tambahkan kode berikut ini pada method up 

Category::factory(10)->hasProducts(5)->create();

// fungsinya adalah untuk menambahkan 10 buah data category ke dalam table category,
// dan menambahkan 5 buah data kedalam masing-masing id pada category tadi,
// sehingga nantinya kita akan memiliki 50 buah data kedalam table product 10x5 = 50 
```
### migration pada seeder 
```phpt
php artisan migrate:fresh --seed

// berfungsi untuk migration factory yang telah kita tambahkan pada seeder tadi 
```


