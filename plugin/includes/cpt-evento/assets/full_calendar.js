jQuery(document).ready(function(){
	jQuery('#calendar').fullCalendar({
		events: themeforce.events,
		header : {
			left:   'title',
			center: '',
			right:  'today prev,next,month,basicWeek,basicDay'
		},
		buttonText: {
			today: 'Hoje',
			day: 'Diário',
			week:'Semanal',
			month:'Mensal'
		},
		monthNames: ["Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro" ],
		monthNamesShort: ['Jan','Feb','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
		dayNames: [ 'Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
		dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb'],
		titleFormat : {
			week: "d[ yyyy]{ ' á '[ MMM] d MMMM 'de' yyyy}",
			day: "dddd, d 'de' MMMM 'de' yyyy"
		},
		columnFormat  : {
			week: "ddd',' d 'de' MMM",
			day: "dddd, d 'de' MMMM 'de' yyyy"
		}
	});
});