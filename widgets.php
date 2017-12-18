<?php
/*

 * Plugin File: Widgets
 * This file contains all the widget code for LWTV
 * @since 1.2.0
	
	Copyright 2017-18 LezWatchTV (email: webmaster@lezwatchtv.com)

	This file is part of LezWatchTV, a plugin for WordPress.

	LezWatchTV is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 2 of the License, or
	(at your option) any later version.

	LezWatchTV is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with WordPress.  If not, see <http://www.gnu.org/licenses/>.
*/

/*
 * class LWTV_Last_Death_Widget
 *
 * Widget to display last queer death
 *
 * @since 1.0
 */
class LWTV_Last_Death_Widget extends WP_Widget {

	/**
	 * Holds widget settings defaults, populated in constructor.
	 */
	protected $defaults;

	/**
	 * Constructor.
	 *
	 * Set the default widget options and create widget.
	 */
	function __construct() {

		$this->defaults = array(
			'title'		=> __( 'Last Queer Death', 'bury-your-queers' ),
		);

		$widget_ops = array(
			'classname'   => 'dead-character deadwidget',
			'description' => __( 'Displays time since the last queer female or trans character death on television.', 'bury-your-queers' ),
		);

		$control_ops = array(
			'id_base' => 'byq-dead-char',
		);

		parent::__construct( 'byq-dead-char', __( 'Last Death  (LezWatchTV)', 'bury-your-queers' ), $widget_ops, $control_ops );
	}

	/**
	 * Echo the widget content.
	 *
	 * @param array $args Display arguments
	 * @param array $instance The settings for the particular instance of the widget
	 */
	function widget( $args, $instance ) {

		extract( $args );
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		echo $args['before_widget'];

		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}

		echo LezWatchTV::last_death();

		echo $args['after_widget'];
	}

	/**
	 * Update a particular instance.
	 *
	 * @param array $new_instance New settings for this instance as input by the user via form()
	 * @param array $old_instance Old settings for this instance
	 * @return array Settings to save or bool false to cancel saving
	 */
	function update( $new_instance, $old_instance ) {
		$new_instance['title'] = strip_tags( $new_instance['title'] );
		return $new_instance;
	}

	/**
	 * Echo the settings update form.
	 *
	 * @param array $instance Current settings
	 */
	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, $this->defaults );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title', 'bury-your-queers' ); ?>: </label>
			<input type="text" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" class="widefat" />
		</p>
		<?php
	}
}

/*
 * class LWTV_Of_The_Day_Widget
 *
 * Widget to display ... Of The Day
 *
 * @since 1.0
 */
class LWTV_Of_The_Day_Widget extends WP_Widget {

	/**
	 * Holds widget settings defaults, populated in constructor.
	 */
	protected $defaults;

	/**
	 * Constructor.
	 *
	 * Set the default widget options and create widget.
	 */
	function __construct() {

		$this->defaults = array(
			'title' => __( 'Of The Day', 'bury-your-queers' ),
			'date'  => '',
		);

		$widget_ops = array(
			'classname'   => 'lwtv-of-the-day otdwidget',
			'description' => __( 'Displays the character, show, or death of the day.', 'bury-your-queers' ),
		);

		$control_ops = array(
			'id_base' => 'lwtv-of-the-day',
		);

		parent::__construct( 'lwtv-of-the-day', __( 'Of The Day (LezWatchTV)', 'bury-your-queers' ), $widget_ops, $control_ops );
	}

	/**
	 * Echo the widget content.
	 *
	 * @param array $args Display arguments
	 * @param array $instance The settings for the particular instance of the widget
	 */
	function widget( $args, $instance ) {

		extract( $args );
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		echo $args['before_widget'];

		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}

		echo LezWatchTV::of_the_day( $type );

		echo $args['after_widget'];
	}

	/**
	 * Update a particular instance.
	 *
	 * @param array $new_instance New settings for this instance as input by the user via form()
	 * @param array $old_instance Old settings for this instance
	 * @return array Settings to save or bool false to cancel saving
	 */
	function update( $new_instance, $old_instance ) {
		$new_instance['title'] = wp_strip_all_tags( $new_instance['title'] );

		// THIS NEEDS TO BE AN OPTION OF THE VALID TYPES
		$valid_types = array( 'character', 'show', 'death' );

		$new_instance['type'] = substr( $new_instance['date'], 0, 5);
		$month = substr( $new_instance['date'], 0, 2);
		$day = substr( $new_instance['date'], 3, 2);
		if ( checkdate( $month, $day, date("Y") ) == false ) $new_instance['date'] = '';
		$new_instance['date']  = wp_strip_all_tags( $new_instance['date'] );

		return $new_instance;
	}

	/**
	 * Echo the settings update form.
	 *
	 * @param array $instance Current settings
	 */
	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, $this->defaults );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title', 'bury-your-queers' ); ?>: </label>
			<input type="text" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" class="widefat" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>"><?php _e( 'Type', 'bury-your-queers' ); ?>: </label>
			<input type="text" id="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'type' ) ); ?>" class="DROPDOWN" value="<?php echo esc_attr( $instance['type'] ); ?>" class="widefat" />
			<br><em><?php _e( 'If left blank, the character of the day will be shown.', 'bury-your-queers' ); ?></em>
		</p>
		<?php
	}
}

/*
 * class LWTV_On_This_Day_Widget
 *
 * Widget to display On This Day...
 *
 * @since 1.0
 */
class LWTV_On_This_Day_Widget extends WP_Widget {

	/**
	 * Holds widget settings defaults, populated in constructor.
	 */
	protected $defaults;

	/**
	 * Constructor.
	 *
	 * Set the default widget options and create widget.
	 */
	function __construct() {

		$this->defaults = array(
			'title' => __( 'On This Day', 'bury-your-queers' ),
			'date'  => '',
		);

		$widget_ops = array(
			'classname'   => 'dead-on-this-day deadwidget',
			'description' => __( 'Displays any queer female or trans TV character who died on this day in years past.', 'bury-your-queers' ),
		);

		$control_ops = array(
			'id_base' => 'byq-on-this-day',
		);

		parent::__construct( 'byq-on-this-day', __( 'On This Day  (LezWatchTV)', 'bury-your-queers' ), $widget_ops, $control_ops );
	}

	/**
	 * Echo the widget content.
	 *
	 * @param array $args Display arguments
	 * @param array $instance The settings for the particular instance of the widget
	 */
	function widget( $args, $instance ) {

		extract( $args );
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		echo $args['before_widget'];

		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}

		$date = ( ! empty( $instance['date'] ) )? $instance['date'] : 'today' ;

		echo LezWatchTV::on_this_day( $date );

		echo $args['after_widget'];
	}

	/**
	 * Update a particular instance.
	 *
	 * @param array $new_instance New settings for this instance as input by the user via form()
	 * @param array $old_instance Old settings for this instance
	 * @return array Settings to save or bool false to cancel saving
	 */
	function update( $new_instance, $old_instance ) {
		$new_instance['title'] = wp_strip_all_tags( $new_instance['title'] );

		$new_instance['date'] = substr( $new_instance['date'], 0, 5);
		$month = substr( $new_instance['date'], 0, 2);
		$day = substr( $new_instance['date'], 3, 2);
		if ( checkdate( $month, $day, date("Y") ) == false ) $new_instance['date'] = '';
		$new_instance['date']  = wp_strip_all_tags( $new_instance['date'] );

		return $new_instance;
	}

	/**
	 * Echo the settings update form.
	 *
	 * @param array $instance Current settings
	 */
	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, $this->defaults );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title', 'bury-your-queers' ); ?>: </label>
			<input type="text" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" class="widefat" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'date' ) ); ?>"><?php _e( 'Date (Optional)', 'bury-your-queers' ); ?>: </label>
			<input type="text" id="<?php echo esc_attr( $this->get_field_id( 'date' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'date' ) ); ?>" class="datepicker" value="<?php echo esc_attr( $instance['date'] ); ?>" class="widefat" />
			<br><em><?php _e( 'If left blank, the date used will be the current day.', 'bury-your-queers' ); ?></em>
		</p>
		<?php
	}
}

/*
 * class LWTV_Statistics_Widget
 *
 * Widget to display death statistics
 *
 * @since 1.2.0
 */
class LWTV_Statistics_Widget extends WP_Widget {

	/**
	 * Holds widget settings defaults, populated in constructor.
	 */
	protected $defaults;

	/**
	 * Constructor.
	 *
	 * Set the default widget options and create widget.
	 */
	function __construct() {

		$this->defaults = array(
			'title' => __( 'Queer Fatality Statistics', 'bury-your-queers' ),
			'type'  => '',
		);

		$widget_ops = array(
			'classname'   => 'dead-stats deadwidget',
			'description' => __( 'Displays the percentage of how many queer female or trans TV characters who have died, and/or how many shows have death.', 'bury-your-queers' ),
		);

		$control_ops = array(
			'id_base' => 'byq-statistics',
		);

		parent::__construct( 'byq-statistics', __( 'Statistics (LezWatchTV)', 'bury-your-queers' ), $widget_ops, $control_ops );
	}

	/**
	 * Echo the widget content.
	 *
	 * @param array $args Display arguments
	 * @param array $instance The settings for the particular instance of the widget
	 */
	function widget( $args, $instance ) {

		extract( $args );
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		echo $args['before_widget'];

		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}

		$type = ( ! empty( $instance['type'] ) )? $instance['type'] : 'all' ;

		echo LezWatchTV::statistics( $type );

		echo $args['after_widget'];
	}

	/**
	 * Update a particular instance.
	 *
	 * @param array $new_instance New settings for this instance as input by the user via form()
	 * @param array $old_instance Old settings for this instance
	 * @return array Settings to save or bool false to cancel saving
	 */
	function update( $new_instance, $old_instance ) {
		$new_instance['title'] = strip_tags( $new_instance['title'] );
		$new_instance['type']  = strip_tags( $new_instance['type'] );
		return $new_instance;
	}

	/**
	 * Echo the settings update form.
	 *
	 * @param array $instance Current settings
	 */
	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, $this->defaults );
		
		$stat_types = array( 'characters', 'shows' );
		
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title', 'bury-your-queers' ); ?>: </label>
			<input type="text" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" class="widefat" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>"><?php _e( 'Type (Optional)', 'bury-your-queers' ); ?>: </label>
			
		<select id="<?php echo $this->get_field_id( 'type' ); ?>" name="<?php echo $this->get_field_name( 'type' ); ?>" class="widefat" style="width:100%;">
			<option value="" selected>All</option>
			<?php foreach( $stat_types as $type) { ?>
				<option <?php selected( $instance[ 'type' ], $type ); ?> value="<?php echo $type; ?>"><?php echo ucfirst( $type ); ?></option>
			<?php } ?>      
		</select>			
			<br><em><?php _e( 'If left blank, the widget will display percentages for both TV shows and characters.', 'bury-your-queers' ); ?></em>
		</p>
		<?php
	}
}