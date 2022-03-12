<?php

namespace FilippoToso\PdfWatermarker\Support;

class Position
{
    protected $name;

    protected $offsetX;
    protected $offsetY;

    public const TOP_LEFT = 'TopLeft';
    public const TOP_CENTER = 'TopCenter';
    public const TOP_RIGHT = 'TopRight';
    public const MIDDLE_LEFT = 'MiddleLeft';
    public const MIDDLE_CENTER = 'MiddleCenter';
    public const MIDDLE_RIGHT = 'MiddleRight';
    public const BOTTOM_LEFT = 'BottomLeft';
    public const BOTTOM_CENTER = 'BottomCenter';
    public const BOTTOM_RIGHT = 'BottomRight';

    protected const VALID_POSITIONS = [
        self::TOP_LEFT, self::TOP_CENTER, self::TOP_RIGHT,
        self::MIDDLE_LEFT, self::MIDDLE_CENTER, self::MIDDLE_RIGHT,
        self::BOTTOM_LEFT, self::BOTTOM_CENTER, self::BOTTOM_RIGHT,
    ];

    /**
     * @param $name
     *
     * @throws \Exception
     */
    public function __construct($name, $offsetX = 0, $offsetY = 0)
    {
        if (!in_array($name, static::VALID_POSITIONS)) {
            throw new \Exception('Unsupported position:' . $name);
        }

        $this->name = $name;
        $this->offsetX = $offsetX;
        $this->offsetY = $offsetY;
    }

    /**
     * @return string name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return int offsetX
     */
    public function getOffsetX()
    {
        return $this->offsetX;
    }

    /**
     * @return int offsetY
     */
    public function getOffsetY()
    {
        return $this->offsetY;
    }

    /**
     * @param Position $position
     *
     * @return bool
     */
    public function equals(Position $position)
    {
        return ($this->name === $position->getName()) && ($this->offsetX == $position->getOffsetX()) && ($this->offsetY == $this->getOffsetY());
    }

    /**
     * @return string name
     */
    /*
    public function __toString()
    {
        return sprintf('%s (%d / %d)'), $this->name, $this->offsetX, $offsetY);
    }
    // */
}
