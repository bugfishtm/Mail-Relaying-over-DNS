<?php
	/* 
		 ____  __  __  ___  ____  ____  ___  _   _ 
		(  _ \(  )(  )/ __)( ___)(_  _)/ __)( )_( )
		 ) _ < )(__)(( (_-. )__)  _)(_ \__ \ ) _ ( 
		(____/(______)\___/(__)  (____)(___/(_) (_) www.bugfish.eu
			 _______ ______ _______ ______  
			(_______|_____ (_______|______) 
			 _  _  _ _____) )     _ _     _ 
			| ||_|| |  __  / |   | | |   | |
			| |   | | |  \ \ |___| | |__/ / 
			|_|   |_|_|   |_\_____/|_____/  
											
		Copyright (C) 2024 Jan Maurice Dahlmanns [Bugfish]

		This program is free software: you can redistribute it and/or modify
		it under the terms of the GNU General Public License as published by
		the Free Software Foundation, either version 3 of the License, or
		(at your option) any later version.

		This program is distributed in the hope that it will be useful,
		but WITHOUT ANY WARRANTY; without even the implied warranty of
		MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
		GNU General Public License for more details.

		You should have received a copy of the GNU General Public License
		along with this program.  If not, see <https://www.gnu.org/licenses/>.
	*/
	require_once("../settings.php");
	x_captcha(_COOKIES_."captcha_default", _CAPTCHA_WIDTH_, _CAPTCHA_HEIGHT_, _CAPTCHA_SQUARES_, _CAPTCHA_ELIPSE_, false, _CAPTCHA_FONT_, _CAPTCHA_RANDOM_);
?>  