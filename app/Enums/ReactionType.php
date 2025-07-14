<?php

namespace App\Enums;

enum ReactionType: string
{
    case LIKE = 'like';
    case LOVE = 'love';
    case HAHA = 'haha';
    case SAD = 'sad';
    case ANGRY = 'angry';

    /**
     * Get the emoji for the reaction.
     */
    public function emoji(): string
    {
        return match ($this) {
            self::LIKE => '👍',
            self::LOVE => '😍',
            self::HAHA => '😂',
            self::SAD => '😢',
            self::ANGRY => '😡',
        };
    }

    /**
     * Get the display name with emoji.
     */
    public function displayName(): string
    {
        return $this->emoji().' '.ucfirst($this->value);
    }

    /**
     * Get all reactions as an array.
     */
    public static function toArray(): array
    {
        return [
            self::LIKE->value => self::LIKE->emoji(),
            self::LOVE->value => self::LOVE->emoji(),
            self::HAHA->value => self::HAHA->emoji(),
            self::SAD->value => self::SAD->emoji(),
            self::ANGRY->value => self::ANGRY->emoji(),
        ];
    }

    /**
     * Get all reaction names.
     */
    public static function names(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get all emojis.
     */
    public static function emojis(): array
    {
        return array_map(fn ($case) => $case->emoji(), self::cases());
    }
}
