import { Controller } from 'stimulus'

export default class extends Controller {
  static targets = ['counter', 'button', 'questions']

  connect () {
  }

  startTest () {
    this.questionsTarget.removeAttribute('hidden')

    this.startCounter()

    this.buttonTarget.remove()
  }

  startCounter () {
    let self = this
    let durationInMinutes = this.element.dataset.duration

    let currentDate = new Date()
    if (durationInMinutes < 1) {
      currentDate.setSeconds(currentDate.getSeconds() + parseFloat(durationInMinutes * 60))
    } else {
      currentDate.setMinutes(currentDate.getMinutes() + parseFloat(durationInMinutes))
    }
    let countDownDate = currentDate.getTime()

    // Update the count down every 1 second
    let x = setInterval(function () {

      // Get today's date and time
      let now = new Date().getTime()

      // Find the distance between now and the count down date
      let distance = countDownDate - now

      // Time calculations for days, hours, minutes and seconds
      let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60))
      let seconds = Math.floor((distance % (1000 * 60)) / 1000)

      // Display the result in the element with id="demo"
      self.counterTarget.innerHTML = minutes + 'm ' + seconds + 's '

      // update server site timestamp
      if (seconds % 5 === 0) {
        self.setTimestamp()
      }

      // If the count down is finished, write some text
      if (distance < 0) {
        clearInterval(x)
        // redirect
        document.form.submit()
      }
    }, 1000)
  }

  setTimestamp() {
    let xhr = new XMLHttpRequest();
    xhr.open("GET", "/questions/ajaxUpdate", true);
    xhr.send(null);
  }
}
