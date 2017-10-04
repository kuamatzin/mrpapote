import moment from 'moment'

export default class DateFormater {
    month(date){
        if (moment.isMoment(date)) {
            return date.month() + 1
        }
        else {
            return moment(date.split('-')[1]).format('M')
        }
    }
}