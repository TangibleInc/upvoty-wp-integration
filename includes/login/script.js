
var i, links = document.getElementsByTagName('a')

for ( i in links ) {
  if (typeof links[i]==='object' && links[i].href) {
    links[i].target = ''
  }
}
// Prevent going to site home via screen title
document.querySelector('#login h1 a').href = 'javascript:void(0)'
