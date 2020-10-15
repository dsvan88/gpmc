<?
$html = '
	<div class="vote-tabs">
		<span class="vote-tabs__item" data-action-type="show-vote-list" data-type="closed">Завершённые</span>
		<span class="vote-tabs__item" data-action-type="show-vote-list" data-type="active">Активные</span>
	</div>';
$html.='<div class="vote-lists">';
$html .= $engine->getVotingListHTML(1);
$html .= '</div>';
echo $html;