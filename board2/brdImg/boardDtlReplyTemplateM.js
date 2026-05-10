var replyItemTemplateString = `
	<div class="mb-3 border-bottom pb-2 reply-item-class" data-bdr_seq="{{bdrSeq}}">
		<div>
			<a href="javascript:modifyReplyForm('{{bdrSeq}}','{{bdrSeqId}}');">수정</a> | 
			<a href="javascript:deleteReply('{{bdrSeq}}','{{bdrSeqId}}');" style="color:red;">삭제</a> | 
			<a href="javascript:fixReply('{{bdrSeq}}','{{bdrFixYN}}','{{bdrSeqId}}');">고정</a>
		</div>
		<div id="replyItemEdit{{bdrSeqId}}" style="display:none;">
			<textarea class="form-control" id="replyItemEditText{{bdrSeqId}}" rows="4" placeholder="댓글내용을 입력해주세요">{{orgReplyContent}}</textarea>
			<div>
				<button class="btn btn-light border flex-shrink-0" onclick="modifyReply('{{bdrSeq}}','{{bdrSeqId}}');">댓글수정</button>
				<button class="btn btn-light border flex-shrink-0" onclick="cancelModifyReplyForm('{{bdrSeq}}','{{bdrSeqId}}');">댓글수정 취소</button>
			</div>
		</div>
		<div class="small fw-bold">{{replyDatetime}}</div>
		<!--<div class="small text-muted mb-1">5분전</div>-->
		<div class="small reply-item-view-class" id="replyItemView{{bdrSeqId}}">{{replyContent}}</div>
	</div>
`;