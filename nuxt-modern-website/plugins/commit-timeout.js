export function commitTimeout (func, time) {
	time = typeof time !== 'undefined' ? time : 0;
	if (process.client) {
		setTimeout(() => {
	  		func()
		}, time)
	} else {
		func()
	}
}