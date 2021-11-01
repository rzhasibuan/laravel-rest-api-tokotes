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

## Menambahkan CRUD pada category and product 

### Tambahkan controller category and product
```phpt
php artisan make:controller CategoryController --api --model=Category
php artisan make:controller ProductController --api --model=Product 

// kode tersebut berfungsi untuk membuat controller resource api 
```

### Menambahkan controller resource pada route api
tambahkan kode berikut ini kedalam directory routes api.php

```phpt
Route::apiResource('products', \App\Http\Controllers\ProductController::class);
Route::apiResource('category', \App\Http\Controllers\CategoryController::class);

// kode tersebut berfungsi untuk membuat route pada controller yang telah kita buat 
```

### melihat list pada route yang telah dibuat 
```phpt
php artisan route:list --compact 
```

### show data pada category 
buka file CategoryController.php 
pada bagian method index tambhakan kode berikut ini 
```phpt
public function index()
{
    Return Category::all(); 
    // berfungsi untuk menampilkan seluruh data yang ada di dalam table
  
    Return Category::get(); 
    // sama halnya juga dengan all dapat menampilkan seluruh data di dalam sebuah table 
    
    Return Category::paginate(10);
    // berfungsi dalam menampilkan sebuah data ke dalam bentuk pagination 
    
    //bisa sesuai kebutuhan kita dalam menampilkan sebuah data 
}
```


### show spesifik data category 
pada method show tambahkan kode berikut ini 
```phpt
public function show(Category $category)
{
    return $category;
}
```

kode di atas yang sering dilakukan pada umumnya dalam menampilkan spesifik data 
tetapi kita juga dapat mengcustome apa saja yang ingin kita tampilkan. 
dengan cara membuat resource file, caranya seperti dibawah ini.

```phpt
php artisan make:resource SingleCategoryResource
```
setelah singleCategoryResource.php berhasil kita buat maka langsung open file tersebut 
pada bagian method toArray tambahkan kode berikut ini
```phpt
public function toArray($request)
{
    return [
        'id' = $this->id,
        'name' = $this->name,
        'slug' = $this->slug,
        'create' = $this->created_at->format('d, F y'),
    ];
}
```
kemudian instansiasi kode tersebut kedalam controller category pada bagian medhod show tadi

```phpt
public function show(Category $category)
{
    return $category;
}

```
rubah kode yang semulanya seperti diatas ini menjadi seperti dibawah ini
```phpt
public function show(Category $category)
{
    return new SingleCategoryResource($category)
}

// kode tersebut berfungsi untuk instansiasi class SingleCategoryResource yang sebelumnya telah kita buat 
```
maka output json nya akan seperti dibawah ini 
```phpt
    "data" : [
        {
            "id" : 1,
            "name" : "ini adalah neme category",
            "slug" : "ini-adalah-slug-category",
            "create" : "11 november 2021"
        }
    ]
```
