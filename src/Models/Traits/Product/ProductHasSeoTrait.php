<?php

namespace IlBronza\Products\Models\Traits\Product;

use IlBronza\CRUD\Traits\Model\HasSeo;

trait ProductHasSeoTrait
{
    use HasSeo;

    public function getDefaultSeoDescription(): ?string
    {
        return $this->getAttribute('short_description')
            ?: $this->getAttribute('description')
            ?: $this->getAttribute('name');
    }

    public function getDefaultSeoOgType(): string
    {
        // Switch to "product" when a public price is available for the entity.
        return 'article';
    }

    public function getDefaultSeoImage(?string $locale = null): ?array
    {
        if (! method_exists($this, 'getMedia')) {
            return null;
        }

        $media = $this->getMedia('catalog')->sortBy('order_column')->first()
            ?? $this->getMedia('cover')->first();

        if (! $media || ! method_exists($media, 'getUrl')) {
            return null;
        }

        $url = $media->getUrl();
        if (! $url) {
            return null;
        }

        if (! str_starts_with($url, 'http://') && ! str_starts_with($url, 'https://')) {
            $url = url($url);
        }

        $width = method_exists($media, 'getCustomProperty') ? $media->getCustomProperty('width') : null;
        $height = method_exists($media, 'getCustomProperty') ? $media->getCustomProperty('height') : null;
        if ((! $width || ! $height) && method_exists($media, 'getPath') && is_file($path = $media->getPath())) {
            $size = @getimagesize($path);
            if (is_array($size)) {
                $width ??= $size[0] ?: null;
                $height ??= $size[1] ?: null;
            }
        }

        return array_filter([
            'url' => $url,
            'width' => $width ? (int) $width : null,
            'height' => $height ? (int) $height : null,
            'alt' => $this->getAttribute('name') ?? $this->getDefaultSeoTitle(),
        ], static fn ($value) => $value !== null && $value !== '');
    }
}
