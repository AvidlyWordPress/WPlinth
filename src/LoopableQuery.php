<?php

namespace WPlinth;

class LoopableQuery extends WP_Query implements Countable, Iterator {

	/**
	 * Have posts even if still at the last one.
	 */
	protected $do_have_posts = true;

	/**
	 * @param array|WP_Query $query The WP_Query or an array of args for WP_Query.
	 */
	public function __construct( $args ) {
		parent::__construct( $args );
	}

	/**
	 * (PHP 5 &gt;= 5.1.0)<br/>
	 * Count elements of an object
	 * @link http://php.net/manual/en/countable.count.php
	 * @return int The custom count as an integer.
	 * </p>
	 * <p>
	 *       The return value is cast to an integer.
	 */
	public function count() {
		return (int) $this->post_count;
	}

	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Return the current element
	 * @link http://php.net/manual/en/iterator.current.php
	 * @return mixed Can return any type.
	 */
	public function current() {
		return $this->post;
	}

	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Move forward to next element
	 * @link http://php.net/manual/en/iterator.next.php
	 * @return void Any returned value is ignored.
	 */
	public function next() {
		if ( $this->current_post + 1 < $this->post_count ) {
			$this->the_post();
		} else {
			$this->do_have_posts = false;
		}
	}

	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Return the key of the current element
	 * @link http://php.net/manual/en/iterator.key.php
	 * @return mixed scalar on success, or null on failure.
	 */
	public function key() {
		return $this->current_post;
	}

	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Checks if current position is valid
	 * @link http://php.net/manual/en/iterator.valid.php
	 * @return boolean The return value will be casted to boolean and then evaluated.
	 *       Returns true on success or false on failure.
	 */
	public function valid() {
		if ( $this->do_have_posts ) {
			if ( ! $this->in_the_loop ) {
				$this->the_post();
			}
			return true;
		}
		$this->rewind_posts();
		wp_reset_postdata();
		return false;
	}

	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Rewind the Iterator to the first element
	 * @link http://php.net/manual/en/iterator.rewind.php
	 * @return void Any returned value is ignored.
	 */
	public function rewind() {
		wp_reset_postdata();
		$this->rewind_posts();
		$this->in_the_loop = false;
	}

}
