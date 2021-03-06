<?php
/**
 * @copyright 2011-2012 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
$lastModified = '2012-09-18T15:37-05';
?>
<discovery>
	<changeset><?php echo $lastModified; ?></changeset>
	<contact>You can email for assistance <?php echo ADMINISTRATOR_EMAIL; ?></contact>
	<key_service>You can request a key at <?php echo OPEN311_KEY_SERVICE; ?></key_service>
	<endpoints>
		<endpoint>
			<specification>http://wiki.open311.org/GeoReport_v2</specification>
			<url><?php echo BASE_URL; ?>/open311/v2</url>
			<changeset><?php echo $lastModified; ?></changeset>
			<type>production</type>
			<formats>
				<format>text/xml</format>
				<format>application/json</format>
			</formats>
		</endpoint>
		<endpoint>
			<specification>http://wiki.open311.org/GeoReport_v2</specification>
			<url>https://bloomington.in.gov/test/open311/v2</url>
			<changeset><?php echo $lastModified; ?></changeset>
			<type>test</type>
			<formats>
				<format>text/xml</format>
				<format>application/json</format>
			</formats>
		</endpoint>
	</endpoints>
</discovery>
