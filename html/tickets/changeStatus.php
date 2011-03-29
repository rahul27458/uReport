<?php
/**
 * @copyright 2011 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param GET ticket_id
 */
if (!userIsAllowed('Tickets')) {
	$_SESSION['errorMessages'][] = new Exception('noAccessAllowed');
	header('Location: '.BASE_URL);
	exit();
}

try {
	if (isset($_REQUEST['ticket_id'])) {
		$ticket = new Ticket($_REQUEST['ticket_id']);
	}
	else {
		throw new Exception('tickets/unknownTicket');
	}
}
catch (Exception $e) {
	$_SESSION['errorMessages'][] = $e;
	header('Location: '.BASE_URL.'/tickets');
	exit();
}

if (isset($_POST['status'])) {
	if ($_POST['status'] == 'closed') {
		header('Location: '.BASE_URL.'/tickets/closeTicket.php?ticket_id='.$ticket->getId());
		exit();
	}
	$ticket->setStatus($_POST['status']);

	// add a record to ticket history
	$history = new TicketHistory();
	$history->setTicket($ticket);
	$history->setAction('statusChange');
	$history->setEnteredByPerson_id($_SESSION['USER']->getPerson_id());
	$history->setActionPerson_id($_SESSION['USER']->getPerson_id());
	$history->setNotes($_POST['notes']);

	try {
		$ticket->save();
		$history->save();
		header('Location: '.$ticket->getURL());
		exit();
	}
	catch (Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}

// Display the view
$template = new Template('tickets');
$template->blocks['ticket-panel'][] = new Block(
	'tickets/ticketInfo.inc',
	array('ticket'=>$ticket,'disableButtons'=>true)
);
$template->blocks['ticket-panel'][] = new Block(
	'tickets/changeStatusForm.inc',
	array('ticket'=>$ticket)
);
if ($ticket->getStatus() != 'closed') {
	$template->blocks['ticket-panel'][] = new Block(
		'tickets/closeTicketForm.inc',
		array('ticket'=>$ticket)
	);
}
$template->blocks['history-panel'][] = new Block(
	'tickets/history.inc',
	array('ticketHistory'=>$ticket->getHistory(),'disableButtons'=>true)
);
$template->blocks['issue-panel'][] = new Block(
	'issues/issueList.inc',
	array('issueList'=>$ticket->getIssues(),'disableButtons'=>true)
);
if ($ticket->getLocation()) {
	$template->blocks['location-panel'][] = new Block(
		'locations/locationInfo.inc',
		array('location'=>$ticket->getLocation())
	);
	$template->blocks['location-panel'][] = new Block(
		'tickets/ticketList.inc',
		array(
			'ticketList'=>new TicketList(array('location'=>$ticket->getLocation())),
			'title'=>'Other tickets for this location',
			'disableButtons'=>true,
			'filterTicket'=>$ticket
		)
	);
}
echo $template->render();