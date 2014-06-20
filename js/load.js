$.get('/example-data.txt', function(fileContents) {
  fileContents = fileContents.split(/\n/);
  console.log(fileContents);
});
