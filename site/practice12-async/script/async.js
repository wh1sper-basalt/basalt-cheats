let content = document.getElementById("content");

async function openPage(alias, to_feedback = false) {
	let promice_fetch = await fetch(`async/${alias}`);

	let result = await promice_fetch.text();

	let promice_feedback = new Promise((resolve, reject) => {
		content.innerHTML = result;
		if (to_feedback) {
			resolve();
		}
	})

	promice_feedback.then(
		function() {
			toFeedback()
		}
	);
}

function toFeedback() {
	let feedback = document.getElementById("feedback");

	window.scrollTo({
		top: feedback.offsetTop,
		behavior: "smooth"
	});
}

openPage("main");
