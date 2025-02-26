# Search

```php
// "title" ustuni ichida "first" yoki "second" bo'lgan barcha yozuvlarni olish
$posts = Post::with('translations')->whereTranslationLike('title', '%Aziz%')
        ->orWhereTranslationLike('title', '%second%')
        ->first();
```
