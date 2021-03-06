<?php
/*
Copyright 2009-2016 John Blackbourn

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

*/

class QM_Output_Html_Languages extends QM_Output_Html {

	public $id = 'languages';

	public function __construct( QM_Collector $collector ) {
		parent::__construct( $collector );
		add_filter( 'qm/output/menus', array( $this, 'admin_menu' ), 80 );
	}

	public function output() {

		$data = $this->collector->get_data();

		if ( empty( $data['languages'] ) ) {
			return;
		}

		echo '<div class="qm" id="' . esc_attr( $this->collector->id() ) . '">';
		echo '<table cellspacing="0">';
		echo '<caption>' . esc_html( sprintf(
			/* translators: %s: Name of current language */
			__( 'Language Setting: %s', 'query-monitor' ),
			$data['locale']
		) ) . '</caption>';
		echo '<thead>';
		echo '<tr>';
		echo '<th>' . esc_html__( 'Text Domain', 'query-monitor' ) . '</th>';
		echo '<th>' . esc_html__( 'Caller', 'query-monitor' ) . '</th>';
		echo '<th colspan="2">' . esc_html__( 'MO File', 'query-monitor' ) . '</th>';
		echo '</tr>';
		echo '</thead>';
		echo '<tbody>';

		$not_found_class = ( substr( $data['locale'], 0, 3 ) === "en_" ) ? '' : 'qm-warn';

		foreach ( $data['languages'] as $mofile ) {

			echo '<tr>';

			echo '<td>' . esc_html( $mofile['domain'] ) . '</td>';
			echo '<td class="qm-nowrap qm-ltr">';
			echo self::output_filename( $mofile['caller']['display'], $mofile['caller']['file'], $mofile['caller']['line'] ); // WPCS: XSS ok.
			echo '</td>';
			echo '<td class="qm-ltr">';
			echo esc_html( QM_Util::standard_dir( $mofile['mofile'], '' ) );
			echo '</td>';

			if ( $mofile['found'] ) {
				echo '<td class="qm-nowrap">';
				echo esc_html( size_format( $mofile['found'] ) );
				echo '</td>';
			} else {
				echo '<td class="' . esc_attr( $not_found_class ) . '">';
				echo esc_html__( 'Not Found', 'query-monitor' );
				echo '</td>';
			}

			echo '</tr>';

		}

		echo '</tbody>';
		echo '</table>';
		echo '</div>';

	}

	public function admin_menu( array $menu ) {

		$data = $this->collector->get_data();
		$args = array(
			'title' => esc_html( $this->collector->name() ),
		);

		$menu[] = $this->menu( $args );

		return $menu;

	}

}

function register_qm_output_html_languages( array $output, QM_Collectors $collectors ) {
	if ( $collector = QM_Collectors::get( 'languages' ) ) {
		$output['languages'] = new QM_Output_Html_Languages( $collector );
	}
	return $output;
}

add_filter( 'qm/outputter/html', 'register_qm_output_html_languages', 81, 2 );
