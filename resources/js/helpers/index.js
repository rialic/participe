import { format, toDate } from 'date-fns'

export const {
    presetForm,
    presetFilter,
    maskDate,
    unmaskDate,
    deepCopy,
    truncateText,
    errorMessage,
    base64ToFile,
    cpfValidated
  } = (() => {
    function presetForm(form) {
      return Object.entries({ ...form })
              .map(([key, value]) => [key, (value && Object.prototype.toString.call(value) === '[object String]') ? value.trim() : value ])
              .reduce((acc, [key, value]) => acc = { ...acc, [key]: value }, {})
    }

    function presetFilter(filter) {
      return Object.entries({ ...filter })
              .map(([key, value]) => [key, value?.trim() ])
              .filter(([_, value]) => value)
              .reduce((acc, [key, value]) => acc = { ...acc, [key]: value }, {})
    }

    function maskDate(date) {
      return format(toDate(date), 'dd/MM/yyyy HH:mm:ss')
    }

    function unmaskDate(date) {
      return format(toDate(date), 'yyyy-MM-dd HH:mm:ss')
    }

    function deepCopy(obj) {
      return JSON.parse(JSON.stringify(obj))
    }

    function truncateText(value, limit) {
      if (value?.length <= limit) {
        return value
      }

      return value?.slice(0, limit).trim() + '...'
    }

    function errorMessage(label) {
      if (Object.keys(this.errors).length) {
        return this.errors[label]?.join(' ') || null
      }
    }

    function base64ToFile(base64String, filename, mimeType) {
      const base64Data = base64String.includes('base64,') ? base64String.split('base64,')[1] : base64String
      const binaryData = atob(base64Data)
      const bytes = new Uint8Array(binaryData.length)

      for (let i = 0; i < binaryData.length; i++) {
        bytes[i] = binaryData.charCodeAt(i)
      }

      const blob = new Blob([bytes], { type: mimeType })
      const file = new File([blob], filename, { type: mimeType, lastModified: new Date().getTime() })

      return file
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
      unmaskDate,
      deepCopy,
      truncateText,
      errorMessage,
      base64ToFile,
      cpfValidated
    }
  })()