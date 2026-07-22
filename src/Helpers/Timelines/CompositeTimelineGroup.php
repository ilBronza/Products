<?php

namespace IlBronza\Products\Helpers\Timelines;

use IlBronza\Timeline\Interfaces\TimelineGroupInterface;

/**
 * Gruppo timeline composto da un padre e un figlio.
 * Serve quando lo stesso figlio compare sotto piu' padri: l'id unisce
 * le due chiavi, altrimenti esisterebbe un gruppo solo e il figlio
 * apparirebbe sotto un padre soltanto.
 * Tutto il resto e' delegato al figlio, perche' il padre e' gia'
 * espresso dal gruppo che lo contiene.
 */
abstract class CompositeTimelineGroup implements TimelineGroupInterface
{
	public function __construct(public TimelineGroupInterface $parent, public TimelineGroupInterface $child)
	{
	}

	static function create(TimelineGroupInterface $parent, TimelineGroupInterface $child) : static
	{
		return new static($parent, $child);
	}

	public function getParent() : TimelineGroupInterface
	{
		return $this->parent;
	}

	public function getChild() : TimelineGroupInterface
	{
		return $this->child;
	}

	public function getTimelineGroupId() : string
	{
		return $this->parent->getTimelineGroupId() . ':' . $this->child->getTimelineGroupId();
	}

	//getKey e getMorphClass servono a TimelineGroupCreatorHelper,
	//che oggi assume un model eloquent
	public function getKey() : string
	{
		return $this->getTimelineGroupId();
	}

	public function getMorphClass() : string
	{
		return $this->child->getMorphClass();
	}

	public function getTimelineGroupName() : string
	{
		return $this->child->getTimelineGroupName();
	}

	public function getTimelineGroupContent() : string
	{
		return $this->child->getTimelineGroupContent();
	}

	public function getTimelineGroupCssStyles() : array
	{
		return $this->child->getTimelineGroupCssStyles();
	}

	public function getTimelineGroupHtmlClasses() : array
	{
		return $this->child->getTimelineGroupHtmlClasses();
	}

	public function getTimelineGroupActions() : array
	{
		return $this->child->getTimelineGroupActions();
	}

	public function getTimelineBindingDataArray() : array
	{
		return $this->child->getTimelineBindingDataArray();
	}

	public function getTimelineGroupGanttUrl() : string
	{
		return $this->child->getTimelineGroupGanttUrl();
	}

	public function getTimelineGroupModalUrl() : ? string
	{
		return $this->child->getTimelineGroupModalUrl();
	}
}
