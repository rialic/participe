import { format, toDate } from 'date-fns'

export const {
    presetForm,
    presetFilter,
    maskDate,
    deepCopy,
    truncateText,
    errorMessage,
    cpfValidated
  } = (() => {
    function presetForm(form) {
      return Object.entries({ ...form })
              .map(([key, value]) => [key, (value && Object.prototype.toString.call(value) === '[object String]') ? value.trim() : value ])
              .reduce((acc, [key, value]) => acc = { ...acc, [key]: value }, {})
    }

    function presetFilter(search, filter) {
      return Object.entries({ ...filter })
              .map(([key, value]) => [key, value || search?.trim() ])
              .filter(([_, value]) => value)
              .reduce((acc, [key, value]) => acc = { ...acc, [key]: value }, {})
    }

    function maskDate(date) {
      return format(toDate(date), 'dd/MM/yyyy HH:mm:ss')
    }

    function deepCopy(obj) {
      return JSON.parse(JSON.stringify(obj))
    }

    function truncateText(value, limit) {
      if (value.length <= limit) {
        return value
      }

      return value.slice(0, limit).trim() + '...'
    }

    function errorMessage(label) {
      if (Object.keys(this.errors).length) {
        return this.errors[label]?.join(' ') || null
      }
    }

    function cpfValidated(cpf) {
      const cpfRegex = /^(?:(\d{3}).(\d{3}).(\d{3})-(\d{2}))$/

      if (!cpfRegex.test(cpf) ||
        cpf === '000.000.000-00' ||
        cpf === '111.111.111-11' ||
        cpf === '222.222.222-22' ||
        cpf === '333.333.333-33' ||
        cpf === '444.444.444-44' ||
        cpf === '555.555.555-55' ||
        cpf === '666.666.666-66' ||
        cpf === '777.777.777-77' ||
        cpf === '888.888.888-88' ||
        cpf === '999.999.999-99') {
        return false
      }

      const numbers = cpf.match(/\d/g).map(Number)
      let sum = numbers.reduce((acc, cur, index) => {
        if (index < 9) {
          return acc + cur * (10 - index)
        }
        return acc
      }, 0)

      let rest = (sum * 10) % 11

      if (rest === 10 || rest === 11) {
        rest = 0
      }

      if (rest !== numbers[9]) {
        return false
      }

      sum = numbers.reduce((acc, cur, index) => {
        if (index < 10) {
          return acc + cur * (11 - index)
        }
        return acc
      }, 0)

      rest = (sum * 10) % 11

      if (rest === 10 || rest === 11) {
        rest = 0
      }

      if (rest !== numbers[10]) {
        return false
      }

      return true
    }

    return {
      presetForm,
      presetFilter,
      maskDate,
      deepCopy,
      truncateText,
      errorMessage,
      cpfValidated
    }
  })()