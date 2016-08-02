Storage.prototype.setArray = function(key, obj) {
	return this.setItem(key, JSON.stringify(obj))
}
Storage.prototype.getArray = function(key) {
	return JSON.parse(this.getItem(key))
}