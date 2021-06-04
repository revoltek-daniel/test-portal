import { Controller } from 'stimulus'

export default class extends Controller {
  connect () {
  }

  startTest () {
    console.log('test')
    document.getElementById('questions').removeAttribute('hidden')
  }
}
