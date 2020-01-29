<?php

namespace App\Http\Models;

use Auth;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * 订单
 * Class Order
 *
 * @package App\Http\Models
 * @mixin Eloquent
 * @property-read mixed   $status_label
 * @property int          $oid
 * @property string       $order_sn      订单编号
 * @property int          $user_id       操作人
 * @property int          $goods_id      商品ID
 * @property int          $coupon_id     优惠券ID
 * @property string|null  $email         邮箱
 * @property int          $origin_amount 订单原始总价，单位分
 * @property int          $amount        订单总价，单位分
 * @property string|null  $expire_at     过期时间
 * @property int          $is_expire     是否已过期：0-未过期、1-已过期
 * @property int          $pay_way       支付方式：1-余额支付、2-有赞云支付
 * @property int          $status        订单状态：-1-已关闭、0-待支付、1-已支付待确认、2-已完成
 * @property Carbon|null  $created_at    创建时间
 * @property Carbon|null  $updated_at    最后一次更新时间
 * @property-read Coupon  $coupon
 * @property-read Goods   $goods
 * @property-read Payment $payment
 * @property-read User    $user
 * @method static Builder|Order newModelQuery()
 * @method static Builder|Order newQuery()
 * @method static Builder|Order query()
 * @method static Builder|Order uid()
 * @method static Builder|Order whereAmount($value)
 * @method static Builder|Order whereCouponId($value)
 * @method static Builder|Order whereCreatedAt($value)
 * @method static Builder|Order whereEmail($value)
 * @method static Builder|Order whereExpireAt($value)
 * @method static Builder|Order whereGoodsId($value)
 * @method static Builder|Order whereIsExpire($value)
 * @method static Builder|Order whereOid($value)
 * @method static Builder|Order whereOrderSn($value)
 * @method static Builder|Order whereOriginAmount($value)
 * @method static Builder|Order wherePayWay($value)
 * @method static Builder|Order whereStatus($value)
 * @method static Builder|Order whereUpdatedAt($value)
 * @method static Builder|Order whereUserId($value)
 */
class Order extends Model
{
	protected $table = 'order';
	protected $primaryKey = 'oid';
	protected $appends = ['status_label'];

	function scopeUid($query)
	{
		return $query->where('user_id', Auth::user()->id);
	}

	function user()
	{
		return $this->hasOne(User::class, 'id', 'user_id');
	}

	function goods()
	{
		return $this->hasOne(Goods::class, 'id', 'goods_id')->withTrashed();
	}

	function coupon()
	{
		return $this->hasOne(Coupon::class, 'id', 'coupon_id')->withTrashed();
	}

	function payment()
	{
		return $this->hasOne(Payment::class, 'oid', 'oid');
	}

	function getOriginAmountAttribute($value)
	{
		return $value/100;
	}

	function setOriginAmountAttribute($value)
	{
		return $this->attributes['origin_amount'] = $value*100;
	}

	function getAmountAttribute($value)
	{
		return $value/100;
	}

	function setAmountAttribute($value)
	{
		return $this->attributes['amount'] = $value*100;
	}
}