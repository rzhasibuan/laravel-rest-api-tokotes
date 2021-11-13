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

### Create Category pada method store
untuk membuat fungsi create kita bisa menambahkan perintah tersebut ini kedalam function store

```phpt
public function store(Request $request)
    {
        try{
            $category = Category::create([
                'name' => $request->name,
                'slug' => strtolower(Str::slug($request->name . '-' . time())),
            ]);

            return response()->json([
                'status' => 'oke',
                'message' => 'Category has been created',
                'product' => new SingleCategoryResource($category),
            ], 201);

        }catch (\Error $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Maaf terjadi kesalahan pada sistem kami '
            ], 500);
        }
    }
```
perintah di atas hanya berfungsi untuk menyimpan data category saja dan nge try catch error. belum dengan menambahkan validasinya datanya
maka itu kita akan membuat validasi datanya kali ini dengan artisan request.
```phpt
php artisan make:request CategoryRequest
```
setelah artisan di atas kita jalankan maka kita telah mendapatkan satu file bernama CategoryRequest.php yang terletak pada directory
app/http/request selanjutnya buka file request tadi dan pada method rules tambahkan perintah berikut ini
```phpt
    public function rules()
    {
        return [
            "name" => ["required","min:4", "max:30"]
        ];
    }
```
disitu kita bisa menambahkan validasi name required atau tidak dan bisa juga menambakan minimal karakternya berapa serta maximalnya berapa
selanjutnya pada method authorize ubah status false nya manjadi true dan kemudian ubah script pada method store kita tadi seperti dibawah ini
```phpt
public function store(CategoryRequest $request)
    {
        try{
            $category = Category::create([
                'name' => $request->name,
                'slug' => strtolower(Str::slug($request->name . '-' . time())),
            ]);

            return response()->json([
                'status' => 'oke',
                'message' => 'Category has been created',
                'product' => new SingleCategoryResource($category),
            ], 201);

        }catch (\Error $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Maaf terjadi kesalahan pada sistem kami '
            ], 500);
        }
    }
```

oke kita berhasil menambahkan validasinya dengan mengunakan artisan request dan sebenarnya kita juga bisa manambahkan validasi tanpa mengunakan artisan request.
sebenarnya caranya hampir sama hanya saja caranya mengunakan method validasi yang di tuliskan di method store kita seperti script dibawah ini
```phpt
public function store(Request $request)
    {
           try{
           
            $this->validate($request, [
                'name' => ['required', 'min:4','max:30']
            ]);

            $category = Category::create([
                'name' => $request->name,
                'slug' => strtolower(Str::slug($request->name . '-' . time())),
            ]);

            return response()->json([
                'status' => 'oke',
                'message' => 'Category has been created',
                'product' => new SingleCategoryResource($category),
            ], 201);

        }catch (\Error $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Maaf terjadi kesalahan pada sistem kami '
            ], 500);
        }
    }
```
sebenarnya disini ada banyak cara sekali untuk membuat validasi begitulah salah satunya

### Update Category pada method update
sebenarnya untuk melakukan update sama hanya dengan melakukan create bedanya saja hanya pada methodnya seperti pada script dibawah ini
```phpt
public function store(CategoryRequest $requestm, Categpry $category)
{
    try{
        $category->update([
            'name' => $request->name,
            'slug' => strtolower(Str::slug($request->name . '-' . time())),
        ]);

        return response()->json([
            'status' => 'oke',
            'message' => 'Category has been created',
            'product' => new SingleCategoryResource($category),
        ], 201);

    }catch (\Error $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Maaf terjadi kesalahan pada sistem kami '
        ], 500);
    }
}
```
sebenarnya untuk melakukan update bisa dengan cara di atas dan bisa juga dengan cara yang lebih sederhana lagi seperti dibawah ini
```phpt
public function store(CategoryRequest $requestm, Categpry $category)
{
    try{
    
        $attributes = $request->toArray();
        $attributes['slug'] = Str::slug($request->name . '-' . time());        
        $category->update($attributes);
        
        return response()->json([
            'status' => 'oke',
            'message' => 'Category has been created',
            'product' => new SingleCategoryResource($category),
        ], 201);

    }catch (\Error $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Maaf terjadi kesalahan pada sistem kami '
        ], 500);
    }
}
```
untuk perintah di atas lebih memfokuskan kepada method toArray yang telah kita buat pada file resource sebelumnya yang dengan mengunakan perintah artisan make:resource

### delete Category pada method destroy
untuk melakukan penghapusan data caranya sangatlah mudah sekali kita hanya tinggal memanfaatkan method delete caranya seperti dibawah ini
```phpt
public function destroy(Category $category)
{
    $category->delete();

    return response()->json([
        'status' => 'oke',
        'message' => 'Category has been deleted'
    ], 200);
}
```
## install laravel sanctum untuk token login api

### install dan konfirguration sanctum
```phpt
composer require laravel/sanctum
```
setelah itu publish configuration dengan menjalankan perintah berikut ini
```phpt
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```
setelah itu kemudian jalankan perintah migrate
```phpt
php artisan migrate
```
yang berguna untuk migration vendor yang telah kita publish tadi dan kemudian setalah itu tambakan script dibawah ini
pada app/http/karnel.php
```phpt
'api' => [
    \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
    'throttle:api',
    \Illuminate\Routing\Middleware\SubstituteBindings::class,
],
```

### implementasi sanctum
buka model User.php dan tambahkan hasApiToken pada bagian use yang ada di dalam class User

### generate token
pertama kita harus buat controller baru
```phpt
php artisan make:controller TokenGeneratorController
```
kemudian buat route url nya pada file api.php
```phpt
route::post('token/generator', TokenGeneratorController::class);
```
kemudian selanjutnya buka controller TokenGeneratorController dan tambahkan method seperti dibawah ini
```phpt
public function __invoke(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    return $user->createToken('web')->plainTextToken;
}
```
untuk mengecek apakah token nya berhasil di generate atau tidak kita bisa masuk ke postman
mengunakan alamat http://api.toko.test/api/token/generate dengan method post serta mengirimkan body json seperti dibawah ini

```phpt
{
    "email" : "emailyangterdaftar@domain.com",
    "password" : "password"
}
```
kemudian send jika berhasil maka akan ada tampil body seperti dibawah ini
```phpt
1|CS3Ol0hqNRWYPT4Cptg00XKYyCKyj0BBetwNhc0V
```
### buat table role
setelah kita membuat sebuah token kita juga membutuhkan sebuah role untuk memvalidasi apakah yang mengakses admin ataukah editor
```phpt
// jalankan perintah berikut ini
php artisan make:model Role -m
```
setelah itu buka file migration yang telah kita buat dan tambahkan script seperti dibawah ini
```phpt
public function up()
{
    Schema::create('roles', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->timestamps();
    });
    
    // table dibawah ini adalah pivot table yang berguna untuk mengabungkan relasi many to many antara table users dan roles 
    Schema::create('role_user', function (Blueprint $table) {
       $table->foreignId('user_id')->constrained();
       $table->foreignId('role_id')->constrained();
       $table->primary(['user_id','role_id']);
    });
}
```
kemudian lakukan migrate:fresh
### buat relasi many to many
untuk membuat relasi many to many kita masuk kedalam mode User.php dan tambahkan script seperti dibawah ini
```phpt
public function roles()
{
    return $this->belongsToMany(Role::class, 'role_user');
}
```
### buat dummy data untuk test roles dengan UserSeeder
jalankan perintah
```phpt
php artisan make:seed UserSeeder
```
kemudian buka file UserSeeder.php
dan tambahkan script dibawah ini
```phpt
 public function run()
    {
        collect([
            [
                'name' => 'reza',
                'email' => 'rzhasibuan@gmail.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'ranggie viona zubainadah',
                'email' => 'ranggie@gmail.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'indra setiawan',
                'email' => 'donok@gmail.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        ])->each(function ($user) {
            User::create($user);
        });
        // fungsi each disini untuk melakukan perulangan sebanyak data array yang ada
        // kemudian di simpan di dalam table user

        collect(['admin','editor'])->each(function ($role){
            Role::create(['name' => $role]);
        });
        // collect di atas untuk membuat atau mengisi table roles dengan field admin dan editor yang di simpan kedalam table Role
  
        User::find(1)->roles()->attach([1]);
        User::find(2)->roles()->attach([2]);
        // fungsi find() untuk mencari id pada table user dan user mana yang ingin kita tambakan sebuah roles
        // fungsi roles()->attach([1]) adalah untuk nambahkan sebuah roles dari relasi many to many yang telah kita buat
        // attach([1]) yang berarti memasukkan 1 yang berupa admin kedalam pivot tables role_user   
    }
```
kemudian setelah itu jalankan artisan:seed
```phpt
php artisan db:seed --class=UserSeeder
```
jika berhasil data akan masuk ke dalam tables roles dan table role_user serta bertambah 3 buah user di table users

### buat fungsi pengecekan roles dengan menambahkan hasRole pada model User.php dan pada AppServiceProvider.php
```phpt
public function hasRole($role)
{
    return $this->roles()->where('name', $role)->exists();
    // berfungsi untuk mencari name pada role yang kelak method nya akan di panggil kedalam method Gate
}
```
dan tambahkan perintah berikut ini kedalam AppServiceProvider.php di bagian method boot
```phpt
Gate::before(function ($user, $ability){
   $user->hasRole('admin') ? true : null;
});
// yang berfungsi jika has role user sama dengan admin maka hasilnya adalah true
```
untuk melakukan simulasi pengecekannya kita bisa mengunakan tinker
```phpt
php artisan tinker
>>> $user = User::find(1);
>>> $user->hasRole('admin');
=> true // jika benar user dengan id 1 memiliki roles sebagai admin maka hasilnya true
=> false // dan jika tidak maka akan menampilkan false
```

### implementasikan roles tersebut kedalam setiap project
