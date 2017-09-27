import moment from 'moment'

export default class DateFormater {
        month(month){
                return moment(month._i).format('M')
        }
}