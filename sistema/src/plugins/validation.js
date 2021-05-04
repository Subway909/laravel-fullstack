import moment from 'moment'

export default {
  required: v => !!(v && String(v).trim()) || 'Campo obrigatório',
  time: v => !v || !!(v && moment(v, 'hh:mm', true).isValid()) || 'Hora inválida',
  date: v => !v || !!(v && moment(v, 'DD/MM/YYYY', true).isValid()) || 'Data inválida',
  datetime: v => !v || !!(v && moment(v, 'DD/MM/YYYY HH:mm', true).isValid()) || 'Data ou hora inválida',
  notzero: v => !v || v > 0 || 'O valor deve ser maior que 0',
  dataFutura: v => !v || !(v && moment(v, 'DD/MM/YYYY').isBefore(moment())) || 'A data não pode ser menor que a data atual',
  dataPassada: v => !v || !(v && moment(v, 'DD/MM/YYYY').isAfter(moment())) || 'A data não pode ser maior que a data atual',
  dataHoraFutura: v => !v || !(v && moment(v, 'DD/MM/YYYY HH:mm').isBefore(moment())) || 'A data não pode ser menor que a data e hora atual',
  dataHoraPassada: v => !v || !(v && moment(v, 'DD/MM/YYYY HH:mm').isAfter(moment())) || 'A data não pode ser maior que a data e hora atual',
  url: v => !v || (/^(ftp|http|https):\/\/[^ "]+$/.test(v)) || 'O link precisa ser uma URL. Verifique se começa com \'http://\' ou \'https://\'',
  email: v => !v || !!v.match(/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i) || 'E-mail inválido!',
  smtp: v => !v || (/^(smtp).[^ "]+$/.test(v)) || 'O link precisa ser uma URL. Verifique se começa com \'smtp\'',
  texto: v => !v || (/^[a-zA-Z\u00C0-\u017F\s]+$/.test(v)) || 'Não é permitido caracteres especiais'
}
