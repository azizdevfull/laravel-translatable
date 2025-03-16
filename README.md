# 📜 Translatable Trait <a href="./app/Traits/Translatable.php">Link</a>

**Translatable** trait Laravel modellarida ko‘p tilli (multilingual) ma’lumotlarni boshqarish uchun ishlatiladi. Bu trait modelga qo‘shilgan tarjima maydonlarini avtomatik ravishda olish va qidirish imkonini beradi.

## 🛠 O‘rnatish

1. `App\Traits\Translatable.php` fayliga quyidagi trait kodini joylashtiring.
2. Modelga `use Translatable;` qo‘shing.
3. `protected $translatedAttributes = ['title', 'content'];` maydonini qo‘shing.
4. Modelingizga `translationModel` va `translationForeignKey` maydonlarini qo‘shib, maxsus sozlash imkoniyatiga ega bo‘ling.

### 1️⃣ Trait’ni loyihaga qo‘shish

```php
use App\Traits\Translatable;
```

## Migrations

posts

```php
Schema::create('posts', function (Blueprint $table) {
    $table->id();
    $table->string('status')->default('active');
    $table->timestamps();
});
```

post_translations

```php
Schema::create('post_translations', function (Blueprint $table) {
    $table->id();
    $table->foreignId('post_id')->constrained()->onDelete('cascade');
    $table->string('locale', 5)->index(); // Misol: en, uz, ru
    $table->string('title');
    $table->text('content')->nullable();
    $table->unique(['post_id', 'locale']);
});
```

2️⃣ Modelga qo‘shish

```php
use Illuminate\Database\Eloquent\Model;
use App\Traits\Translatable;

class Post extends Model
{
    use Translatable;

    protected $fillable = ['status'];

    // Tarjima qilinadigan maydonlar
    public $translatedAttributes = ['title', 'content'];

    // Tarjima modeli va foreign key'ni moslashtirish yozmasangiz
    // default ModelTranslation va model_id
    protected $translationModel = PostTranslation::class; // majburi emas
    protected $translationForeignKey = 'post_id'; // majburi emas
}
```

3️⃣ Tarjima modeli

```php
use Illuminate\Database\Eloquent\Model;

class PostTranslation extends Model
{
    protected $fillable = ['post_id', 'locale', 'title', 'content'];

    public $timestamps = false;
}
```

# 📜 Translatable Trait Qo‘llanilishi

<strong>Translatable</strong> trait Laravel modellarida ko‘p tilli (multilingual) ma’lumotlarni boshqarish uchun ishlatiladi. Bu trait modelga qo‘shilgan tarjima maydonlarini avtomatik ravishda olish, qidirish va o‘chirish imkonini beradi.

# 🔧 1. Qanday ishlaydi?

<ul  >
<li  ><strong  >Tarjima maydonlariga to‘g‘ridan-to‘g‘ri <code  >$post-&gt;title</code> orqali murojaat qilish mumkin.</strong><br  >
<code  >title</code> va <code  >content</code> maydonlari uchun <strong  >odatdagi tildagi</strong> tarjima avtomatik olinadi.</li>
<li  ><strong  >Qidiruv (<code  >whereTranslationLike</code>, <code  >whereTranslation</code>) imkoniyatlari mavjud.</strong></li>
<li  ><strong  >Tarjimalarni qo‘shish va o‘chirish (<code  >setTranslations</code>, <code  >deleteTranslations</code>) qo‘llab-quvvatlanadi.</strong></li>
</ul>

# 🚀 2. Tarjimalarni olish

## 1️⃣ Modeldan ma’lumot olish

```php
$post = Post::find(1);
echo $post->title; // Joriy tilga mos keladigan title'ni chiqaradi
echo $post->content; // Joriy tilga mos keladigan content'ni chiqaradi
```

## 2️⃣ Barcha tarjimalarni olish

```php
$post = Post::with('translations')->find(1);
foreach ($post->translations as $translation) {
    echo "{$translation->locale}: {$translation->title} \n";
}
```

## 3️⃣ Muayyan tilni tanlash

```php
$post = Post::find(1);
echo $post->translation('uz')->title; // Faqat "uz" tilidagi title'ni olish
echo $post->translation('ru')->content; // "ru" tilidagi content'ni olish
```

# 🔍 3. Tarjimalar bo‘yicha qidirish

## 1️⃣ <strong>LIKE bilan qidirish (<code  >whereTranslationLike</code>)</strong>

```php
$posts = Post::whereTranslationLike('title', 'Laravel')->get();
```

<p>🔹 Yuqoridagi kod <code  >title</code> maydoni <strong  >"Laravel"</strong> so‘zini o‘z ichiga olgan barcha postlarni joriy til bo‘yicha qaytaradi.</p>

```php
$posts = Post::whereTranslationLike('title', 'Laravel', 'uz')->get();
```

<p data-start="1784" data-end="1826">🔹 Faqat <code data-start="1793" data-end="1797">uz</code> tilidagi postlarni qidirish.</p>

## <h3 data-start="1837" data-end="1899">2️⃣ <strong data-start="1845" data-end="1899">Aniq qiymat bo‘yicha qidirish (<code data-start="1878" data-end="1896">whereTranslation</code>)</strong></h3>

```php
$posts = Post::whereTranslation('title', 'Laravel Tips')->get();
```

<p data-start="1984" data-end="2037">🔹 <strong data-start="1987" data-end="2027">Aynan "Laravel Tips" nomli postlarni</strong> qidirish.</p>

```php
$posts = Post::whereTranslation('title', 'Laravel Tips', 'uz')->get();
```

## <h3 data-start="2198" data-end="2277">3️⃣ <strong data-start="2206" data-end="2277">OR bilan qidirish (<code data-start="2227" data-end="2251">orWhereTranslationLike</code> &amp; <code data-start="2254" data-end="2274">orWhereTranslation</code>)</strong></h3>

```php
$posts = Post::whereTranslationLike('title', 'Laravel')
             ->orWhereTranslationLike('title', 'Vue')
             ->get();
```

<p data-start="2431" data-end="2504">🔹 <strong data-start="2434" data-end="2458">"Laravel" yoki "Vue"</strong> so‘zlarini o‘z ichiga olgan postlarni topish.</p>

```php
$posts = Post::whereTranslation('title', 'Laravel Tips')
             ->orWhereTranslation('title', 'Vue Tips')
             ->get();
```

# 📝 4. Tarjimalarni qo‘shish

<h3 data-start="2770" data-end="2838">1️⃣ <strong data-start="2778" data-end="2838">Tarjimalarni yaratish yoki yangilash (<code data-start="2818" data-end="2835">setTranslations</code>)</strong></h3>

```php
$post = Post::create(['status' => 'published']);

$post->setTranslations([
    'en' => ['title' => 'Laravel Tips', 'content' => 'Laravel is awesome!'],
    'uz' => ['title' => 'Laravel Maslahatlar', 'content' => 'Laravel juda zo‘r!'],
]);
```

<p data-start="3102" data-end="3200">🔹 <strong data-start="3105" data-end="3200">Agar post oldin yaratilgan bo‘lsa, mavjud tarjimalarni yangilaydi, bo‘lmasa yangi qo‘shadi.</strong></p>

```php
$post->setTranslations([
    'de' => ['title' => 'Laravel Tipps', 'content' => 'Laravel ist großartig!']
]);
```

<p data-start="3331" data-end="3391">🔹 <strong data-start="3334" data-end="3391">Nemis tilida yangi tarjimani qo‘shish yoki yangilash.</strong></p>

# ❌ 5. Tarjimalarni o‘chirish (deleteTranslations)

<h3 data-start="3463" data-end="3496">1️⃣ <strong data-start="3471" data-end="3496">Bitta tilni o‘chirish</strong></h3>

```php
$post->deleteTranslations('de');
```

<p data-start="3549" data-end="3604">🔹 <strong data-start="3552" data-end="3604">Faqat "de" (nemis) tilidagi tarjimani o‘chirish.</strong></p>

<h3 data-start="3615" data-end="3657">2️⃣ <strong data-start="3623" data-end="3657">Bir nechta tarjimani o‘chirish</strong></h3>

```php
$post->deleteTranslations(['de', 'en']);
```

<h3 data-start="3786" data-end="3827">3️⃣ <strong data-start="3794" data-end="3827">Barcha tarjimalarni o‘chirish</strong></h3>

```php
$post->deleteTranslations();
```

<p data-start="3876" data-end="3924">🔹 <strong data-start="3879" data-end="3924">Post'ning barcha tarjimalarini o‘chirish.</strong></p>

# 🔥 6. Tarjimalarni yuklashni optimallashtirish (Eager Loading)

<p data-start="4008" data-end="4114">Tarjimalarni <code data-start="4021" data-end="4043">with('translations')</code> bilan yuklash kerak, aks holda <strong data-start="4075" data-end="4091">N+1 muammosi</strong> yuzaga kelishi mumkin.</p>

```php
$posts = Post::with('translations')->get();

foreach ($posts as $post) {
    echo $post->title; // Eager loading ishlaydi
}
```

<p data-start="4262" data-end="4386"><strong data-start="4262" data-end="4274">Eslatma:</strong> Agar <code data-start="4280" data-end="4302">with('translations')</code> ishlatilmasa, har safar <code data-start="4327" data-end="4341">$post-&gt;title</code> chaqirilganda yangi so‘rov (query) ishlaydi.</p>

# 🎯 7. Full Example

```php
use App\Models\Post;

// ✅ 1. Post yaratish
$post = Post::create(['status' => 'published']);

// ✅ 2. Tarjimalarni qo‘shish
$post->setTranslations([
    'en' => ['title' => 'Laravel Tips', 'content' => 'Laravel is awesome!'],
    'uz' => ['title' => 'Laravel Maslahatlar', 'content' => 'Laravel juda zo‘r!'],
]);

// ✅ 3. Muayyan tilni olish
echo $post->translation('uz')->title; // "Laravel Maslahatlar"

// ✅ 4. LIKE bilan qidirish
$posts = Post::whereTranslationLike('title', 'Laravel')->get();

// ✅ 5. Tarjimalarni o‘chirish
$post->deleteTranslations('uz'); // Faqat "uz" tilidagi tarjimani o‘chirish

$post->deleteTranslations(); // Barcha tarjimalarni o‘chirish
```
